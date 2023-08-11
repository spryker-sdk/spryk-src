<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\DataBuilder;

use SimpleXMLElement;
use SprykerSdk\Spryk\Model\Spryk\Builder\Transfer\AbstractTransferSpryk;

class DataBuilderPropertySpryk extends AbstractTransferSpryk
{
    /**
     * @var string
     */
    public const DATA_BUILDER_RULE = 'dataBuilderRule';

    /**
     * @var string
     */
    protected const SPRYK_NAME = 'dataBuilderProperty';

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface|null $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());

        // @codeCoverageIgnoreStart
        if (!$resolved) {
            $this->log(sprintf('Could not find an XMl by path <fg=yellow>%s</>', $this->getTargetPath()));

            return;
        }
        // @codeCoverageIgnoreEnd

        /** @var \SimpleXMLElement $simpleXMLElement */
        $simpleXMLElement = $resolved->getSimpleXmlElement();

        // Multiple transfers
        $transferDefinitions = $this->getTransferDefinitions();

        foreach ($transferDefinitions as $transferName => $properties) {
            $transferXMLElement = $this->findTransferByName($simpleXMLElement, $transferName);
            foreach ($properties as $property) {
                $dataBuilderRule = $property['dataBuilderRule'] ?? $this->dataBuilderRuleByProperty($property['name'], $property['type']);
                $this->addProperty($transferXMLElement, $transferName, $property['name'], $dataBuilderRule);
            }
        }
    }

    /**
     * @return array<string, array<int, array<string, string|null>>>
     */
    protected function getSingleProperty(): array
    {
        return [
            $this->getTransferName() => [
                [
                    'name' => $this->getPropertyName(),
                    'type' => $this->getPropertyType(),
                    'dataBuilderRule' => $this->getDataBuilderRule(),
                ],
            ],
        ];
    }

    /**
     * We can't have default values for database id fields. If we would not skip those the helper used in tests
     * would throw an exception when they try to insert an Entity with an id.
     *
     * [Propel\Runtime\Exception\PropelException] Cannot insert a value for auto-increment primary key
     *
     * @param string $propertyName
     * @param string $transferName
     *
     * @return bool
     */
    protected function isAutoIncrementField(string $propertyName, string $transferName): bool
    {
        $databaseIdField = sprintf('id%s', $transferName);

        if ($propertyName === $databaseIdField) {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    protected function getDataBuilderRule(): ?string
    {
        $dataBuilderRule = $this->arguments->getArgument(static::DATA_BUILDER_RULE)->getValue();

        if ($dataBuilderRule) {
            return $dataBuilderRule;
        }

        return $this->dataBuilderRuleByProperty(
            $this->getPropertyName(),
            $this->getPropertyType(),
        );
    }

    /**
     * @param \SimpleXMLElement $transferXMLElement
     * @param string $transferName
     * @param string $propertyName
     * @param string|null $dataBuilderRule
     *
     * @return void
     */
    protected function addProperty(
        SimpleXMLElement $transferXMLElement,
        string $transferName,
        string $propertyName,
        ?string $dataBuilderRule,
    ): void {
        // When no data builder rule can be found it must be a reference to another transfer object and we must ignore it.
        if (!$dataBuilderRule || $this->isAutoIncrementField($propertyName, $transferName)) {
            return;
        }
        $propertyXMLElement = $this->findPropertyByName($transferXMLElement, $propertyName);

        if ($propertyXMLElement) {
            $this->log(sprintf('Property by name <fg=yellow>%s</> already exists.', $propertyName));

            return;
        }

        $propertyXMLElement = $transferXMLElement->addChild('property');
        $propertyXMLElement->addAttribute('name', $propertyName);
        $propertyXMLElement->addAttribute('dataBuilderRule', $dataBuilderRule);

        $this->log(sprintf('Added dataBuilderXMLElement property <fg=green>%s.%s</>', $transferName, $propertyName));
    }

    /**
     * @param string $propertyName
     * @param string $propertyType
     *
     * @return string|null
     */
    protected function dataBuilderRuleByProperty(string $propertyName, string $propertyType): ?string
    {
        if ($propertyName === 'uuid') {
            return 'uuid()';
        }

        switch ($propertyType) {
            case 'string':
                return 'word()';
            case 'int':
                return 'randomDigit()';
            case 'bool':
                return 'boolean()';
            case 'array':
                return 'randomElements()';
        }

        return null;
    }
}
