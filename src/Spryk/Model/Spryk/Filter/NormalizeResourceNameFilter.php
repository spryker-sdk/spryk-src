<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\DashToCamelCase;

/**
 * Filter is used to convert a string where words are separated by "-"
 * into a camelCased string.
 *
 * Example:
 * $this->filter('/foo-bars') === `FooBars';
 * $this->filter('/foo-bars/{som-param}') === `FooBars';
 *
 * @deprecated Use one of the ResourceNameTo* filters instead
 * @see \SprykerSdk\Spryk\Model\Spryk\Filter\ResourceNameToModuleNameFilter
 * @see \SprykerSdk\Spryk\Model\Spryk\Filter\ResourceNameToModelNameFilter
 * @see \SprykerSdk\Spryk\Model\Spryk\Filter\ResourceNameToControllerNameFilter
 */
class NormalizeResourceNameFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'normalizeResourceName';

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
        $value = trim($value, '/');
        $values = explode('/', $value);
        $value = current($values);

        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($value));
    }
}
