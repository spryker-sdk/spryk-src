<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class ResolveOrganizationInFqcn implements CallbackInterface
{
    /**
     * @var string
     */
    protected const CALLBACK_NAME = 'ResolveOrganizationInFqcn';

    /**
     * @var string
     */
    protected const ARGUMENT_ORGANIZATION = 'organization';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::CALLBACK_NAME;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed $value
     *
     * @return mixed|void
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        if (!$argumentCollection->hasArgument(static::ARGUMENT_ORGANIZATION)) {
            return $value;
        }

        $organization = $argumentCollection->getArgument(static::ARGUMENT_ORGANIZATION)->getValue();

        return (string)preg_replace('/(\w+)/', $organization, $value, 1);
    }
}
