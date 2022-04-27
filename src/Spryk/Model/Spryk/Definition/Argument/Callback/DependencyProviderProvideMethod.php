<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

/**
 * Callback that resolves based on the application + the optional layer one of:
 * - provideDependencies (Glue)
 * - provideServiceLayerDependencies (Client)
 * - provideServiceDependencies (Service)
 * - provideBusinessLayerDependencies (Zed + Business)
 * - provideCommunicationLayerDependencies (Zed + Communication)
 * - providePersistenceLayerDependencies (Zed + Persistence)
 *
 * Used by:
 */
class DependencyProviderProvideMethod implements CallbackInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'DependencyProviderProvideMethod';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @return string
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value): string
    {
        if ($argumentCollection->getArgument('application', true)->getValue() === 'Glue') {
            return 'provideDependencies';
        }

        if ($argumentCollection->getArgument('application', true)->getValue() === 'Client') {
            return 'provideServiceDependencies';
        }

        if ($argumentCollection->getArgument('application', true)->getValue() === 'Service') {
            return 'provideServiceLayerDependencies';
        }

        if ($argumentCollection->getArgument('layer', true)->getValue() === 'Business') {
            return 'provideBusinessLayerDependencies';
        }
        if ($argumentCollection->getArgument('layer', true)->getValue() === 'Communication') {
            return 'provideCommunicationLayerDependencies';
        }

        return 'providePersistenceLayerDependencies';
    }
}
