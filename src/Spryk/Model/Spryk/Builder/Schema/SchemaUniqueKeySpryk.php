<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Schema;

use SimpleXMLElement;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;

class SchemaUniqueKeySpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const KEY_NAME = 'keyName';

    /**
     * @var string
     */
    public const COLUMNS = 'columns';

    /**
     * @var string
     */
    protected const SPRYK_NAME = 'SchemaUniqueKey';

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
    protected function addColumn(SimpleXMLElement $tableXmlElement): void
    {
        $uniqueKeyName = $this->getUniqueKeyName();

        if ($this->isUniqueKeyDefinedInTable($tableXmlElement, $uniqueKeyName)) {
            return;
        }

        $uniqueKeyXmlElement = $tableXmlElement->addChild('unique');
        $uniqueKeyXmlElement->addAttribute('name', $uniqueKeyName);

        foreach ($this->getUniqueKeyColumns() as $column) {
            $uniqueKeyColumnXmlElement = $uniqueKeyXmlElement->addChild('unique-column');
            $uniqueKeyColumnXmlElement->addAttribute('name', $column);
        }
    }

    /**
     * @return string
     */
    protected function getUniqueKeyName(): string
    {
        return $this->arguments->getArgument(static::KEY_NAME)->getValue();
    }

    /**
     * @return array<string>
     */
    protected function getUniqueKeyColumns(): array
    {
        return $this->arguments->getArgument(static::COLUMNS)->getValue();
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     * @param string $uniqueKeyName
     *
     * @return bool
     */
    protected function isUniqueKeyDefinedInTable(SimpleXMLElement $simpleXmlElement, string $uniqueKeyName): bool
    {
        $columnXmlElements = $simpleXmlElement->xpath('//unique');

        if ($columnXmlElements !== false) {
            foreach ($columnXmlElements as $tableXmlElement) {
                if ((string)$tableXmlElement['name'] === $uniqueKeyName) {
                    return true;
                }
            }
        }

        return false;
    }
}
