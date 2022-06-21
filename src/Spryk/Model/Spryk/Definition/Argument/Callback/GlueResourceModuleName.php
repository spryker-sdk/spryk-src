<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class GlueResourceModuleName implements CallbackInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'GlueResourceModuleName';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        $resourceName = $value ?? $argumentCollection->getArgument('module')->getValue();
        $applicationType = $argumentCollection->getArgument('applicationType')->getValue();

        if ($applicationType === 'Backend') {
            return $this->ensureBackendApiModuleName($resourceName);
        }

        return $this->ensureStorefrontApiModuleName($resourceName);
    }

    /**
     * @param string $resourceName
     *
     * @return string
     */
    protected function ensureBackendApiModuleName(string $resourceName): string
    {
        if (!preg_match('/BackendApi$/', $resourceName)) {
            return $resourceName . 'BackendApi';
        }

        return $resourceName;
    }

    /**
     * @param string $resourceName
     *
     * @return string
     */
    protected function ensureStorefrontApiModuleName(string $resourceName): string
    {
        if (!preg_match('/StorefrontApi$/', $resourceName)) {
            return $resourceName . 'StorefrontApi';
        }

        return $resourceName;
    }
}
