<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Filter is used to add the `Controller` suffix to a string
 * if it is not present in the given one.
 *
 * Example:
 * $this->filter('CreateProduct') === `CreateProductController';
 * $this->filter('RemoveProductController') === `RemoveProductController';
 */
class EnsureResourceSuffixFilter implements FilterInterface
{
    /**
     * @var string
     */
    public const RESOURCE_SUFFIX = 'Resource';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'ensureResourceSuffix';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        if (mb_substr($value, - mb_strlen(static::RESOURCE_SUFFIX)) !== static::RESOURCE_SUFFIX) {
            $value = $value . static::RESOURCE_SUFFIX;
        }

        return ucfirst($value);
    }
}
