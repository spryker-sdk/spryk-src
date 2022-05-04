<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;

/**
 * Filter is used to convert a camelCased string
 * into string where words are separated by "-".
 *
 * Example:
 * $this->filter('stringExample') === `string-example';
 */
class CamelCaseToDashFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'camelCaseToDash';

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
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($value);
    }
}
