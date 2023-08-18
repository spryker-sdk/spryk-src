<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\Collection\CallbackCollection;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\Collection\CallbackCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\Resolver\CallbackArgumentResolver;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\Resolver\CallbackArgumentResolverInterface;

class CallbackFactory
{
    public function createCallbackArgumentResolver(): CallbackArgumentResolverInterface
    {
        return new CallbackArgumentResolver(
            $this->createCallbackCollection(),
        );
    }

    public function createCallbackCollection(): CallbackCollectionInterface
    {
        return new CallbackCollection([
            $this->createZedFactoryMethodNameCallback(),
            $this->createZedCommunicationFactoryMethodNameCallback(),
            $this->createZedBusinessModelTargetFilenameCallback(),
            $this->createZedCommunicationModelTargetFilenameCallback(),
            $this->createZedBusinessModelInterfaceTargetFilenameCallback(),
            $this->createZedCommunicationModelInterfaceTargetFilenameCallback(),
            $this->createZedBusinessModelSubDirectoryCallback(),
            $this->createZedTestMethodNameCallback(),
            $this->createClassNameShortCallback(),
            $this->createEnsureControllerSuffixCallback(),
            $this->createRemoveControllerSuffixCallback(),
            $this->createEnsureActionSuffixCallback(),
            $this->createRemoveActionSuffixCallback(),
            $this->createEnsureRestAttributesTransferAffixCallback(),
            $this->createGlueProcessorModelTargetFilenameCallback(),
            $this->createGlueProcessorModelInterfaceTargetFilenameCallback(),
            $this->createGlueProcessorModelSubDirectoryCallback(),
            $this->createGlueProcessorFactoryMethodNameCallback(),
            $this->createGlueResourceInterfaceTargetFilenameCallback(),
            $this->createGlueResourceTargetFilenameCallback(),
            $this->createEnsureResourceSuffixCallback(),
            $this->createEnsureInterfaceSuffixCallback(),
            $this->createRemoveRestApiSuffixCallback(),
        ]);
    }

    public function createZedCommunicationFactoryMethodNameCallback(): CallbackInterface
    {
        return new ZedCommunicationFactoryMethodNameCallback();
    }

    public function createZedFactoryMethodNameCallback(): CallbackInterface
    {
        return new ZedBusinessFactoryMethodNameCallback();
    }

    public function createZedBusinessModelTargetFilenameCallback(): CallbackInterface
    {
        return new ZedBusinessModelTargetFilenameCallback();
    }

    public function createZedCommunicationModelTargetFilenameCallback(): CallbackInterface
    {
        return new ZedCommunicationModelTargetFilenameCallback();
    }

    public function createZedBusinessModelInterfaceTargetFilenameCallback(): CallbackInterface
    {
        return new ZedBusinessModelInterfaceTargetFilenameCallback();
    }

    public function createZedCommunicationModelInterfaceTargetFilenameCallback(): CallbackInterface
    {
        return new ZedCommunicationModelInterfaceTargetFilenameCallback();
    }

    public function createZedBusinessModelSubDirectoryCallback(): CallbackInterface
    {
        return new ZedBusinessModelSubDirectoryCallback();
    }

    public function createZedTestMethodNameCallback(): CallbackInterface
    {
        return new ZedTestMethodNameCallback();
    }

    public function createClassNameShortCallback(): CallbackInterface
    {
        return new ClassNameShortCallback();
    }

    public function createEnsureControllerSuffixCallback(): CallbackInterface
    {
        return new EnsureControllerSuffixCallback();
    }

    public function createRemoveControllerSuffixCallback(): CallbackInterface
    {
        return new RemoveControllerSuffixCallback();
    }

    public function createEnsureActionSuffixCallback(): CallbackInterface
    {
        return new EnsureActionSuffixCallback();
    }

    public function createRemoveActionSuffixCallback(): CallbackInterface
    {
        return new RemoveActionSuffixCallback();
    }

    public function createEnsureRestAttributesTransferAffixCallback(): CallbackInterface
    {
        return new EnsureRestAttributesTransferAffixCallback();
    }

    public function createGlueProcessorModelTargetFilenameCallback(): CallbackInterface
    {
        return new GlueProcessorModelTargetFilenameCallback();
    }

    public function createGlueProcessorModelInterfaceTargetFilenameCallback(): CallbackInterface
    {
        return new GlueProcessorModelInterfaceTargetFilenameCallback();
    }

    public function createGlueProcessorModelSubDirectoryCallback(): CallbackInterface
    {
        return new GlueProcessorModelSubDirectoryCallback();
    }

    public function createGlueProcessorFactoryMethodNameCallback(): CallbackInterface
    {
        return new GlueProcessorFactoryMethodNameCallback();
    }

    public function createGlueResourceInterfaceTargetFilenameCallback(): CallbackInterface
    {
        return new GlueResourceInterfaceTargetFilenameCallback();
    }

    public function createGlueResourceTargetFilenameCallback(): CallbackInterface
    {
        return new GlueResourceTargetFilenameCallback();
    }

    public function createEnsureResourceSuffixCallback(): CallbackInterface
    {
        return new EnsureResourceSuffixCallback();
    }

    public function createEnsureInterfaceSuffixCallback(): CallbackInterface
    {
        return new EnsureInterfaceSuffixCallback();
    }

    public function createRemoveRestApiSuffixCallback(): CallbackInterface
    {
        return new RemoveRestApiSuffixCallback();
    }
}
