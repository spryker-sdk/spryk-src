<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Schema;

use SimpleXMLElement;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;

class SchemaPropertySpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const PROPERTY_NAME = 'propertyName';

    /**
     * @var string
     */
    public const PROPERTY_TYPE = 'propertyType';

    /**
     * @var string
     */
    public const REQUIRED = 'required';

    /**
     * @var string
     */
    public const AUTO_INCREMENT = 'autoIncrement';

    /**
     * @var string
     */
    public const PRIMARY_KEY = 'primaryKey';

    /**
     * @var string
     */
    public const DEFAULT_VALUE = 'defaultValue';

    /**
     * @var string
     */
    public const SIZE = 'size';

    /**
     * @var string
     */
    protected const SPRYK_NAME = 'schemaProperty';

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

        $this->addColumn($tableXmlElement);
    }

    protected function getSchemaName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_NAME);
    }

    protected function findTableByName(SimpleXMLElement $simpleXmlElement, string $schemaName): ?SimpleXMLElement
    {
        foreach ($simpleXmlElement->children() as $schemaXmlElement) {
            if ((string)$schemaXmlElement['name'] === $schemaName) {
                return $schemaXmlElement;
            }
        }

        return null;
    }

    protected function addColumn(SimpleXMLElement $tableXmlElement): void
    {
        $columnName = $this->getPropertyByName(static::PROPERTY_NAME);

        if ($this->isColumnDefinedInTable($tableXmlElement, $columnName)) {
            return;
        }

        $columnXmlElement = $tableXmlElement->addChild('column');

        $columnXmlElement->addAttribute('name', $columnName);
        $columnXmlElement->addAttribute('type', $this->getPropertyByName(static::PROPERTY_TYPE));

        $this->fillNonRequiredAttributes($columnXmlElement);
    }

    protected function getPropertyByName(string $propertyName): string
    {
        return $this->arguments->getArgument($propertyName)->getValue();
    }

    protected function isColumnDefinedInTable(SimpleXMLElement $simpleXmlElement, string $columnName): bool
    {
        $columnXmlElements = $simpleXmlElement->xpath('//column');

        foreach ($columnXmlElements as $tableXmlElement) {
            if ((string)$tableXmlElement['name'] === $columnName) {
                return true;
            }
        }

        return false;
    }

    protected function fillNonRequiredAttributes(SimpleXMLElement $columnXmlElement): void
    {
        $nonRequiredProperties = [
            static::REQUIRED,
            static::AUTO_INCREMENT,
            static::PRIMARY_KEY,
            static::DEFAULT_VALUE,
            static::SIZE,
        ];

        foreach ($nonRequiredProperties as $propertyName) {
            $propertyValue = $this->getPropertyByName($propertyName);
            if ($propertyValue) {
                $columnXmlElement->addAttribute($propertyName, $propertyValue);
            }
        }
    }
}
