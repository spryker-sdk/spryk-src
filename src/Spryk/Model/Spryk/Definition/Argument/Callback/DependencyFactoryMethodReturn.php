<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

/**
 * Callback that resolves based on the level (project or core) and the dependencyType (Client, Facade, Service) the output of the factory method:
 * - \Organization\Application\DependentModule\DependentModuleClientInterface (Client - Project)
 * - \Organization\Application\Module\Dependency\Client\ModuleToDependentModuleClientInterface (Client - Core)
 *
 * - \Organization\Application\DependentModule\Business\DependentModuleFacadeInterface (Facade - Project)
 * - \Organization\Application\Module\Dependency\Facade\ModuleToDependentModuleFacadeInterface (Facade - Core)
 *
 * - \Organization\Application\DependentModule\Business\DependentModuleServiceInterface (Service - Project)
 * - \Organization\Application\Module\Dependency\Service\ModuleToDependentModuleServiceInterface (Service - Core)
 *
 * Used by:
 */
class DependencyFactoryMethodReturn implements CallbackInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'DependencyFactoryMethodReturn';
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     * @param mixed|null $value
     *
     * @return string
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value): string
    {
        if ($argumentCollection->hasArgument('mode') && $argumentCollection->getArgument('mode')->getValue() === 'project') {
            return sprintf(
                '\%s\%s\%s\%s%sInterface',
                $argumentCollection->getArgument('organization', true),
                $argumentCollection->getArgument('application', true),
                $argumentCollection->getArgument('module', true),
                $argumentCollection->getArgument('dependentModule', true),
                $argumentCollection->getArgument('dependencyType', true),
            );
        }

        return sprintf(
            '\%s\%s\%s\Dependency\%s\%sTo%s%sInterface',
            $argumentCollection->getArgument('organization', true),
            $argumentCollection->getArgument('application', true),
            $argumentCollection->getArgument('module', true),
            $argumentCollection->getArgument('dependencyType', true),
            $argumentCollection->getArgument('module', true),
            $argumentCollection->getArgument('dependentModule', true),
            $argumentCollection->getArgument('dependencyType', true),
        );
    }
}
