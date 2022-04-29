<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Filter is used to convert a string
 * representing any type-hinted array into an argument description.
 *
 * Example:
 * $this->filter(`\Organization\Module\Class[] $classes') === 'array $classes';
 * $this->filter(`array $classes') === 'array $classes';
 */
class TypedArrayFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const ARRAY_TYPE_HINT = 'array';

    /**
     * @var string
     */
    protected const NULLABLE_TYPE_HINT = '?';

    /**
     * @var string
     */
    protected const FILTER_NAME = 'typedArrayConvert';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::FILTER_NAME;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        $initialParameters = preg_split('/(\s*,*\s*)*,+(\s*,*\s*)*/', $value);
        if (!is_array($initialParameters)) {
            return $value;
        }

        $filteredParameters = [];
        foreach ($initialParameters as $parameter) {
            $parameterExploded = explode(' ', $parameter, 2);
            $parameterExploded[0] = $this->convertParameterToTypeHint($parameterExploded[0]);
            $parameter = implode(' ', $parameterExploded);

            $filteredParameters[] = $parameter;
        }

        return implode(', ', $filteredParameters);
    }

    /**
     * @param string $parametersString
     *
     * @return string
     */
    protected function convertParameterToTypeHint(string $parametersString): string
    {
        $parameters = array_map('trim', explode('|', $parametersString));
        $filteredParameter = '';

        foreach ($parameters as $parameter) {
            if ($this->isNullType($parameter) | $this->isMixedType($parameter)) {
                continue;
            }

            if ($this->isTypedArray($parameter)) {
                $filteredParameter = static::ARRAY_TYPE_HINT;
            } else {
                $filteredParameter = $parameter;

                break;
            }
        }

        return $this->isNullableParameter($parametersString) ? $this->makeParameterNullable($filteredParameter) : $filteredParameter;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isTypedArray(string $value): bool
    {
        return (bool)preg_match('((.*?)\[\]$)', $value);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isNullType(string $value): bool
    {
        return $value === 'null';
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isMixedType(string $value): bool
    {
        return $value === 'mixed';
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isNullableParameter(string $value): bool
    {
        return strpos($value, static::NULLABLE_TYPE_HINT) === 0 || (bool)preg_match('/(\|null$)|(\|null\|)|(^null\|)/', $value);
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    protected function makeParameterNullable(string $parameter): string
    {
        return strpos($parameter, static::NULLABLE_TYPE_HINT) === 0 ? $parameter : static::NULLABLE_TYPE_HINT . $parameter;
    }
}
