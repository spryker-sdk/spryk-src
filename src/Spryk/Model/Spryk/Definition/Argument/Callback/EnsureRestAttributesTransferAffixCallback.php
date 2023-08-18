<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class EnsureRestAttributesTransferAffixCallback implements CallbackInterface
{
    /**
     * @var string
     */
    protected const CALLBACK_NAME = 'EnsureRestAttributesTransferAffix';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::CALLBACK_NAME;
    }

    /**
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        $value = (string)$value;
        $value = $this->ensurePrefix($value);

        return $this->ensureSuffix($value);
    }

    protected function ensurePrefix(string $value): string
    {
        $prefix = 'Rest';
        if (substr_compare($value, $prefix, 0, strlen($prefix)) === 0) {
            return $value;
        }

        return $prefix . $value;
    }

    protected function ensureSuffix(string $value): string
    {
        $suffix = 'Attributes';
        if (str_ends_with($value, $suffix)) {
            return $value;
        }

        return $value . $suffix;
    }
}
