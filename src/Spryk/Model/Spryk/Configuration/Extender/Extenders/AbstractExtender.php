<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\Extenders;

use SprykerSdk\Spryk\SprykConfig;

abstract class AbstractExtender
{
    /**
     * @var string
     */
    protected const NAME_PLACEHOLDER_MODULE = '{{ module }}';

    /**
     * @var string
     */
    protected const NAME_PLACEHOLDER_LAYER = '{{ layer }}';

    /**
     * @var string
     */
    protected const NAME_APPLICATION_LAYER_ZED = 'Zed';

    public function __construct(protected SprykConfig $config)
    {
    }

    /**
     * @return array
     */
    protected function getArguments(array $sprykConfig): array
    {
        return $sprykConfig[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS] ?? $sprykConfig;
    }

    /**
     * @return array
     */
    protected function setArguments(array $arguments, array $sprykConfig): array
    {
        if (isset($sprykConfig[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS])) {
            $sprykConfig[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS] = $arguments;

            return $sprykConfig;
        }

        return array_merge($sprykConfig, $arguments);
    }

    /**
     * @return string|null
     */
    protected function getDevelopmentLayer(array $sprykConfig, array $context): ?string
    {
        if (isset($context['mode'])) {
            return $context['mode'];
        }

        return $sprykConfig[SprykConfig::NAME_ARGUMENT_MODE] ?? $context['mode'] ?? null;
    }

    /**
     * @return bool
     */
    protected function isProject(array $sprykConfig, array $context): bool
    {
        return $this->getDevelopmentLayer($sprykConfig, $context) === SprykConfig::NAME_DEVELOPMENT_LAYER_PROJECT;
    }

    /**
     * @return bool
     */
    protected function isCore(array $sprykConfig, array $context): bool
    {
        return $this->getDevelopmentLayer($sprykConfig, $context) === SprykConfig::NAME_DEVELOPMENT_LAYER_CORE;
    }

    /**
     * @return bool
     */
    protected function isBoth(array $sprykConfig, array $context): bool
    {
        return $this->getDevelopmentLayer($sprykConfig, $context) === SprykConfig::NAME_DEVELOPMENT_LAYER_BOTH;
    }
}
