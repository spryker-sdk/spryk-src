<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use InvalidArgumentException;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class GetClassNameFromTargetCallback implements CallbackInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'GetClassNameFromTarget';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        if ($value) {
            return $value;
        }

        if (!$argumentCollection->hasArgument('target') || !$argumentCollection->getArgument('target')->getValue()) {
            throw new InvalidArgumentException(sprintf('Could not find "target" in your argument collection for Spryk "%s"', $argumentCollection->getSprykName()));
        }

        $target = $argumentCollection->getArgument('target')->getValue();
        $targetFragments = explode('\\', $target);

        return array_pop($targetFragments);
    }
}
