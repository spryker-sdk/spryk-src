<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Filter is used to convert a string where words are separated by "-"
 * into a camelCased string.
 *
 * Example:
 * $this->filter('FooBarsBackendApi') === `FooBars';
 * $this->filter('FooBarsFrontendApi') === `FooBars';
 * $this->filter('FooBarsApi') === `FooBars';
 */
class RemoveApiSuffixFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'removeApiSuffix';

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
        $value = (string)preg_replace('/BackendApi$/', '', $value);
        $value = (string)preg_replace('/StorefrontApi$/', '', $value);

        return (string)preg_replace('/Api$/', '', $value);
    }
}
