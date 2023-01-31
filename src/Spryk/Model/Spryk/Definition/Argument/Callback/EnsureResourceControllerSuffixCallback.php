<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class EnsureResourceControllerSuffixCallback implements CallbackInterface
{
    /**
     * @var string
     */
    public const RESOURCE_CONTROLLER_SUFFIX = 'ResourceController';

    /**
     * @var string
     */
    public const CONTROLLER_SUFFIX = 'Controller';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'EnsureResourceControllerSuffixCallback';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        // When we have the ResourceController suffix just return.
        if (mb_substr($value, - mb_strlen(static::RESOURCE_CONTROLLER_SUFFIX)) === static::RESOURCE_CONTROLLER_SUFFIX) {
            return ucfirst($value);
        }

        // When we get a controller name like FooBarController, remove the controller suffix before adding the new one to prevent having FooBarControllerResourceController
        if (mb_substr($value, - mb_strlen(static::CONTROLLER_SUFFIX)) === static::CONTROLLER_SUFFIX) {
            $value = mb_substr($value, 0, mb_strlen($value) - mb_strlen(static::CONTROLLER_SUFFIX));
        }

        $value = $value . static::RESOURCE_CONTROLLER_SUFFIX;

        return ucfirst($value);
    }
}
