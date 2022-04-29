<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Schema;

use SimpleXMLElement;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;

class SchemaBehaviorSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const PARAMETER_NAMES = 'parameterNames';

    /**
     * @var string
     */
    public const PARAMETER_VALUES = 'parameterValues';

    /**
     * @var string
     */
    public const BEHAVIOR_NAME = 'behaviorName';

    /**
     * @var string
     */
    protected const SPRYK_NAME = 'SchemaBehavior';

    /**
     * @var string
     */
    public const ARGUMENT_NAME = 'name';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYK_NAME;
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());
        $simpleXmlElement = $resolved->getSimpleXmlElement();
        $schemaFileName = $this->getSchemaName();

        $tableXmlElement = $this->findTableByName($simpleXmlElement, $schemaFileName);

        if (!$tableXmlElement) {
            $this->log(sprintf(
                'Could not find tableXmlElement by name <fg=green>%s</> in <fg=green>%s</>',
                $schemaFileName,
                $this->getTargetPath(),
            ));

            return;
        }

        $this->addBehavior($tableXmlElement);
    }

    /**
     * @return string
     */
    protected function getSchemaName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_NAME);
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     * @param string $schemaName
     *
     * @return \SimpleXMLElement|null
     */
    protected function findTableByName(SimpleXMLElement $simpleXmlElement, string $schemaName): ?SimpleXMLElement
    {
        foreach ($simpleXmlElement->children() as $schemaXmlElement) {
            if ((string)$schemaXmlElement['name'] === $schemaName) {
                return $schemaXmlElement;
            }
        }

        return null;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return void
     */
    protected function addBehavior(SimpleXMLElement $tableXmlElement): void
    {
        $behaviorNameName = $this->getBehaviorName();

        if ($this->isBehaviorDefinedInTable($tableXmlElement, $behaviorNameName)) {
            return;
        }

        $uniqueKeyXmlElement = $tableXmlElement->addChild('behavior');
        $uniqueKeyXmlElement->addAttribute('name', $behaviorNameName);

        $parameterNames = $this->getParameterNames();
        $parameterValues = $this->getParameterValues();

        foreach ($parameterNames as $index => $parameterName) {
            if (!isset($parameterValues[$index])) {
                $this->log(sprintf(
                    'Could not find parameter value for parameter name <fg=green>%s</>',
                    $parameterName,
                ));
            }

            $uniqueKeyColumnXmlElement = $uniqueKeyXmlElement->addChild('parameter');
            $uniqueKeyColumnXmlElement->addAttribute('name', $parameterName);
            $uniqueKeyColumnXmlElement->addAttribute('value', $parameterValues[$index]);
        }
    }

    /**
     * @return string
     */
    protected function getBehaviorName(): string
    {
        return $this->arguments->getArgument(static::BEHAVIOR_NAME)->getValue();
    }

    /**
     * @return array<string>
     */
    protected function getParameterNames(): array
    {
        return $this->arguments->getArgument(static::PARAMETER_NAMES)->getValue();
    }

    /**
     * @return array<string>
     */
    protected function getParameterValues(): array
    {
        return $this->arguments->getArgument(static::PARAMETER_VALUES)->getValue();
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     * @param string $behaviorName
     *
     * @return bool
     */
    protected function isBehaviorDefinedInTable(SimpleXMLElement $simpleXmlElement, string $behaviorName): bool
    {
        $columnXmlElements = $simpleXmlElement->xpath('//behavior');

        if ($columnXmlElements !== false) {
            foreach ($columnXmlElements as $tableXmlElement) {
                if ((string)$tableXmlElement['name'] === $behaviorName) {
                    return true;
                }
            }
        }

        return false;
    }
}
