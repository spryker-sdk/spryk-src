<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

/**
 * Callback that resolves the class to be extended by the extendCandidates argument
 */
class ResolveExtends implements CallbackInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface
     */
    protected FileResolverInterface $fileResolver;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     */
    public function __construct(FileResolverInterface $fileResolver)
    {
        $this->fileResolver = $fileResolver;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'ResolveExtends';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @return string
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value): string
    {
        if ($value) {
            if (!is_array($value)) {
                return $value;
            }

            foreach ($value as $extendCandidate) {
                if ($this->fileResolver->hasResolved($extendCandidate) || class_exists($extendCandidate)) {
                    return $extendCandidate;
                }
            }

            // Return last always as fallback TODO check if classes can be autoloaded properly. If so we don't need this fallback.
            return end($value);
        }

        return '';
    }
}
