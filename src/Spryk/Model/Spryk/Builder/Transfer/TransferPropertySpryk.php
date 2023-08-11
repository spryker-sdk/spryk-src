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

        // Some Transfer properties can't be made strict as they are not strict in modules.
        if (!in_array($transferName, $this->strictTransfersBlacklist) || in_array($propertyName, $this->strictPropertiesWhitelist)) {
            $propertyXMLElement->addAttribute('strict', 'true');
        }

        if ($singular) {
            $propertyXMLElement->addAttribute('singular', $singular);
        }

        $this->log(sprintf('Added transferXMLElement property <fg=green>%s.%s</>', $transferName, $propertyName));
    }
}
