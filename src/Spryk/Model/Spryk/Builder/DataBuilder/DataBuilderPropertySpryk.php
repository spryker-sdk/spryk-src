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
    public const PROPERTY_TYPE = 'propertyType';

    /**
     * @var string
     */
    public const PROPERTY_NAME = 'propertyName';

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

        $transferName = $this->getTransferName();

        /** @var \SimpleXMLElement $transferXMLElement */
        $transferXMLElement = $this->findTransferByName($simpleXMLElement, $transferName);

        $properties = $this->getProperties();

        if ($properties) {
            foreach ($properties as $propertyParts) {
                $propertyDefinition = explode(':', trim($propertyParts));

                if ($this->isAutoIncrementField($propertyDefinition[0], $transferName)) {
                    continue;
                }

                $this->addProperty($transferXMLElement, $transferName, $propertyDefinition[0], $propertyDefinition[1], $this->dataBuilderRuleByProperty($propertyDefinition[0], $propertyDefinition[1]));
            }

            return;
        }

        $propertyName = $this->getPropertyName();

        if ($this->isAutoIncrementField($propertyName, $transferName)) {
            return;
        }

        $propertyType = $this->getPropertyType();
        $dataBuilderRule = $this->getDataBuilderRule();

        $this->addProperty($transferXMLElement, $transferName, $propertyName, $propertyType, $dataBuilderRule);
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
     * @param \SimpleXMLElement $transferXMLElement
     * @param string $propertyName
     *
     * @return \SimpleXMLElement|null
     */
    protected function findPropertyByName(SimpleXMLElement $transferXMLElement, string $propertyName): ?SimpleXMLElement
    {
        foreach ($transferXMLElement->children() as $propertyXMLElement) {
            if ((string)$propertyXMLElement['name'] === $propertyName) {
                return $propertyXMLElement;
            }
        }

        return null;
    }

    /**
     * @param \SimpleXMLElement $transferXMLElement
     * @param string $transferName
     * @param string $propertyName
     * @param string $propertyType
     * @param string|null $dataBuilderRule
     *
     * @return void
     */
    protected function addProperty(
        SimpleXMLElement $transferXMLElement,
        string $transferName,
        string $propertyName,
        string $propertyType,
        ?string $dataBuilderRule,
    ): void {
        // When no data builder rule can be found it must be a reference to another transfer object and we must ignore it.
        if (!$dataBuilderRule) {
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
     * @return array|null
     */
    protected function getProperties(): ?array
    {
        $properties = $this->arguments
            ->getArgument(static::PROPERTY_NAME)
            ->getValue();

        // When this property is an array it was executed with:
        // --property propertyA --property propertyB ...
        // or with
        // --property propertyA:string --property propertyB:int ...
        if (is_array($properties)) {
            return $properties;
        }

        // When this argument contains a `:` this Spryk was called in a way that multiple properties should be added with one call
        // This will most likely come from other SDK tools to have fewer calls to this Spryk.
        // Examples:
        // --property propertyA:string
        // --property propertyA:string,propertyB:int
        if (strpos($properties, ':') !== false) {
            return explode(',', $properties);
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getPropertyName(): string
    {
        return $this->getStringArgument(static::PROPERTY_NAME);
    }

    /**
     * @return string
     */
    protected function getPropertyType(): string
    {
        return $this->getStringArgument(static::PROPERTY_TYPE);
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

        $propertyType = $this->getPropertyType();
        $propertyName = $this->getPropertyName();

        return $this->dataBuilderRuleByProperty($propertyName, $propertyType);
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
            return 'unique()->uuid()';
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
