<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Transfer;

use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\DashToCamelCase;
use SimpleXMLElement;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;

abstract class AbstractTransferSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const TRANSFERS_PROPERTIES = 'transfersProperties';

    /**
     * @var string
     */
    public const ARGUMENT_NAME = 'name';
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
    public const SINGULAR = 'singular';

    /**
     * @var string
     */
    protected const SPRYK_NAME = 'implemented by derived classes';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYK_NAME;
    }

    /**
     * @return array|null
     */
    protected function getTransferDefinitions(): ?array
    {
        if ($this->arguments->hasArgument(static::TRANSFERS_PROPERTIES) && !empty($this->arguments->getArgument(static::TRANSFERS_PROPERTIES)->getValue())) {
            // TransferA&propertyA:string,propertyB:int:singular;TransferB&propertyA:string,propertyB:int:singular
            $transfersProperties = $this->arguments->getArgument(static::TRANSFERS_PROPERTIES)->getValue();
            $transfersProperties = explode(';', $transfersProperties);

            $transferDefinitions = [];

            foreach ($transfersProperties as $transferDefinition) {
                // $transferDefinition = TransferA&propertyA:string,propertyB:int:singular
                [$transferName, $transferProperties] = explode('#', $transferDefinition);
                $transferProperties = explode(',', $transferProperties);
                foreach ($transferProperties as $properties) {
                    $transferDefinitions[$transferName][] = $this->getPropertiesFromString($properties);
                }
            }

            return $transferDefinitions;
        }

        $transferName = $this->getTransferName();
        $properties = $this->getProperties();

        if ($properties) {
            $transferDefinitions = [];

            foreach ($properties as $propertyParts) {
                $transferDefinitions[$transferName][] = $this->getPropertiesFromString($propertyParts);
            }

            return $transferDefinitions;
        }

        return [
            $transferName => [
                [
                    'name' => $this->getPropertyName(),
                    'type' => $this->getPropertyType(),
                    'singular' => $this->getSingular(),
                ]
            ]
        ];
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
     * @param string $properties
     * @return array
     */
    protected function getPropertiesFromString(string $properties): array
    {
        $propertyDefinition = explode(':', trim($properties));

        return [
            'name' => $propertyDefinition[0],
            'type' => $propertyDefinition[1],
            'singular' => $propertyDefinition[2] ?? null,
        ];
    }

    /**
     * @param \SimpleXMLElement $simpleXMLElement
     * @param string $transferName
     *
     * @return \SimpleXMLElement
     */
    protected function findTransferByName(SimpleXMLElement $simpleXMLElement, string $transferName): SimpleXMLElement
    {
        /** @var \SimpleXMLElement $transferXMLElement */
        foreach ($simpleXMLElement->children() as $transferXMLElement) {
            if ((string)$transferXMLElement['name'] === $transferName) {
                return $transferXMLElement;
            }
        }

        $transferNodeXmlElement = $simpleXMLElement->addChild('transfer');
        $transferNodeXmlElement->addAttribute('name', $transferName);

        return $transferNodeXmlElement;
    }

    /**
     * @return string
     */
    protected function getTransferName(): string
    {
        $dashToCamelCaseFilter = $this->getDashToCamelCase();

        return $dashToCamelCaseFilter->filter($this->getStringArgument(static::ARGUMENT_NAME));
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
    protected function getSingular(): ?string
    {
        if (!$this->arguments->hasArgument(static::SINGULAR)) {
            return null;
        }

        $singular = $this->arguments->getArgument(static::SINGULAR)->getValue();

        if ($singular) {
            return $singular;
        }

        return null;
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
     * @return \Laminas\Filter\FilterChain
     */
    protected function getDashToCamelCase(): FilterChain
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new DashToCamelCase());

        return $filterChain;
    }
}
