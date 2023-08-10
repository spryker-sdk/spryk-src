<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Transfer;

use SimpleXMLElement;

class TransferPropertySpryk extends AbstractTransferSpryk
{
    /**
     * @var string
     */
    protected const SPRYK_NAME = 'transferProperty';

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());
        $simpleXMLElement = $resolved->getSimpleXmlElement();

        // Multiple transfers
        $transferDefinitions = $this->getTransferDefinitions();

        foreach ($transferDefinitions as $transferName => $properties) {
            $transferXMLElement = $this->findTransferByName($simpleXMLElement, $transferName);
            foreach ($properties as $property) {
                $this->addProperty($transferXMLElement, $transferName, $property['name'], $property['type'], $property['singular']);
            }
        }
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
     * @param string|null $singular
     *
     * @return void
     */
    protected function addProperty(
        SimpleXMLElement $transferXMLElement,
        string $transferName,
        string $propertyName,
        string $propertyType,
        ?string $singular = null,
    ): void {
        $propertyXMLElement = $this->findPropertyByName($transferXMLElement, $propertyName);

        if ($propertyXMLElement) {
            return;
        }

        $propertyXMLElement = $transferXMLElement->addChild('property');
        $propertyXMLElement->addAttribute('name', $propertyName);
        $propertyXMLElement->addAttribute('type', $propertyType);

        if ($singular) {
            $propertyXMLElement->addAttribute('singular', $singular);
        }

        $this->log(sprintf('Added transferXMLElement property <fg=green>%s.%s</>', $transferName, $propertyName));
    }

    /**
     * Properties can have the following formats:
     * - MessageA&messages:MessageA:message,propertyB:int;MessageB&propertyA:string,propertyB:string
     * - messages:MessageA:message,propertyB:int
     *
     *
     * @return array|null
     */
    protected function getProperties(): ?array
    {
        $properties = $this->arguments
            ->getArgument(static::PROPERTY_NAME)
            ->getValue();

        // When this property is an array it was executed with:
        // --propertyName propertyA --propertyB ...
        // or with
        // --propertyName propertyA:string --propertyB:int ...
        if (is_array($properties)) {
            return $properties;
        }

        // When this argument contains a `:` this Spryk was called in a way that multiple properties should be added with one call
        // This will most likely come from other SDK tools to have fewer calls to this Spryk.
        // Examples:
        // --propertyName propertyA:string
        // --propertyName propertyA:string,propertyB:int
        if (strpos($properties, ':') !== false) {
            return explode(',', $properties);
        }

        return null;
    }

    /**
     * @return bool
     */
    protected function isTransferPropertyDefined(): bool
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());
        $simpleXMLElement = $resolved->getSimpleXmlElement();

        $transferName = $this->getTransferName();
        $propertyName = $this->getPropertyName();

        $transferXMLElement = $this->findTransferByName($simpleXMLElement, $transferName);

        if (!$transferXMLElement) {
            return false;
        }

        $propertyXMLElement = $this->findPropertyByName($transferXMLElement, $propertyName);

        if (!$propertyXMLElement) {
            return false;
        }

        return true;
    }
}
