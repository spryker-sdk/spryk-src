<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class RemoveControllerSuffixCallback implements CallbackInterface
{
    /**
     * @var string
     */
    public const CONTROLLER_SUFFIX = 'Controller';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'RemoveControllerSuffixCallback';
    }

    /**
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        if (mb_substr($value, - mb_strlen(static::CONTROLLER_SUFFIX)) === static::CONTROLLER_SUFFIX) {
            $value = mb_substr($value, 0, mb_strlen($value) - mb_strlen(static::CONTROLLER_SUFFIX));
        }

        return ucfirst($value);
    }
}
