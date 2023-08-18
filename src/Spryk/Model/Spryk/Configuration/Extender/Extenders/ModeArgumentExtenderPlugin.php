<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\Extenders;

use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderPluginInterface;
use SprykerSdk\Spryk\SprykConfig;

class ModeArgumentExtenderPlugin extends AbstractExtender implements SprykConfigurationExtenderPluginInterface
{
    /**
     * @param array $sprykConfig
     * @param array $context
     *
     * @return array
     */
    public function extend(array $sprykConfig, array $context): array
    {
        return $this->addModeArgument($sprykConfig, $context);
    }

    protected function addModeArgument(array $sprykConfig, array $context): array
    {
        $arguments = $this->getArguments($sprykConfig);

        if (isset($arguments[SprykConfig::NAME_ARGUMENT_MODE]) || !isset($context['mode'])) {
            return $sprykConfig;
        }

        $arguments[SprykConfig::NAME_ARGUMENT_MODE] = [
            SprykConfig::NAME_ARGUMENT_KEY_VALUE => $context['mode'],
        ];

        return $this->setArguments($arguments, $sprykConfig);
    }
}
