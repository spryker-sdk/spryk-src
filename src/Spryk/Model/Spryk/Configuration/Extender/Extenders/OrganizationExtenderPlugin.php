<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\Extenders;

use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderPluginInterface;
use SprykerSdk\Spryk\SprykConfig;

class OrganizationExtenderPlugin extends AbstractExtender implements SprykConfigurationExtenderPluginInterface
{
    /**
     * @param array $sprykConfig
     * @param array $context
     *
     * @return array
     */
    public function extend(array $sprykConfig, array $context): array
    {
        $arguments = $this->getArguments($sprykConfig);

        if (
            !isset($arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION])
            || !isset($arguments[SprykConfig::NAME_ARGUMENT_MODE][SprykConfig::NAME_ARGUMENT_KEY_VALUE])
        ) {
            return $sprykConfig;
        }

        $arguments = $this->buildProjectOrganization($arguments);
        $arguments = $this->buildCoreOrganization($arguments);
        $arguments = $this->setArguments($arguments, $sprykConfig);

        if (isset($context[SprykConfig::NAME_ARGUMENT_ORGANIZATION]) && isset($arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION])) {
            unset($arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_DEFAULT], $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_VALUES]);
            $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $context[SprykConfig::NAME_ARGUMENT_ORGANIZATION];
        }

        return $arguments;
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return array<mixed>
     */
    protected function buildProjectOrganization(array $arguments): array
    {
        if ($arguments[SprykConfig::NAME_ARGUMENT_MODE][SprykConfig::NAME_ARGUMENT_KEY_VALUE] !== SprykConfig::NAME_DEVELOPMENT_LAYER_PROJECT) {
            return $arguments;
        }

        $projectNamespace = $this->config->getProjectNamespace();
        $projectNamespaces = $this->config->getProjectNamespaces();

        $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_DEFAULT] = $projectNamespace;
        $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_VALUES] = $projectNamespaces;

        return $arguments;
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return array<mixed>
     */
    protected function buildCoreOrganization(array $arguments): array
    {
        if ($arguments[SprykConfig::NAME_ARGUMENT_MODE][SprykConfig::NAME_ARGUMENT_KEY_VALUE] !== SprykConfig::NAME_DEVELOPMENT_LAYER_CORE) {
            return $arguments;
        }

        $coreNamespaces = $this->config->getCoreNamespaces();

        $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_VALUES] = $coreNamespaces;

        return $arguments;
    }
}
