<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer;

class ArgumentDefinitionNormalizer implements ArgumentDefinitionNormalizerInterface
{
    /**
     * Prepares argument values from different types of syntax to work correctly later.
     *
     * @param mixed $argumentDefinition
     *
     * @return array
     */
    public function normalizeArgumentDefinition($argumentDefinition): array
    {
        if (is_array($argumentDefinition)) {
            // Collect all value elements, expecting that they will have only numeric keys
            $argumentDefinitionOnlyValueKeys = array_filter(array_keys($argumentDefinition), 'is_int');
            // Extract the values based on the collected list of keys
            $argumentDefinitionOnlyValue = array_map(function ($key) use ($argumentDefinition) {
                return $argumentDefinition[$key];
            }, $argumentDefinitionOnlyValueKeys);

            if ($argumentDefinitionOnlyValue) {
                // Remove all elements of values, so that later we can put them on the correct key
                foreach ($argumentDefinition as $fieldKey => $value) {
                    if (is_int($fieldKey)) {
                        unset($argumentDefinition[$fieldKey]);
                    }
                }
            }

            if (!isset($argumentDefinition['value']) && count($argumentDefinitionOnlyValue)) {
                $argumentDefinition['value'] = $argumentDefinitionOnlyValue;
            }

            return $argumentDefinition;
        }

        return [
            'value' => $argumentDefinition,
        ];
    }
}
