<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Validator\Rules;

use SprykerSdk\Spryk\SprykConfig;

class DevelopmentLayerRule implements ConfigurationValidatorRuleInterface
{
    /**
     * @var string
     */
    protected $errorMessage;

    public function __construct(protected SprykConfig $config)
    {
    }

    /**
     * @param array $sprykConfig
     *
     * @return bool
     */
    public function validate(array $sprykConfig): bool
    {
        if (!isset($sprykConfig[SprykConfig::NAME_ARGUMENT_MODE])) {
            return true;
        }

        if (in_array($sprykConfig[SprykConfig::NAME_ARGUMENT_MODE], $this->config->getAvailableDevelopmentLayers(), true)) {
            return true;
        }

        $this->buildErrorMessage($sprykConfig[SprykConfig::NAME_ARGUMENT_MODE]);

        return false;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    protected function buildErrorMessage(string $value): void
    {
        $template = 'Development layer `%s` is unavailable. Please set `mode` one of: %s.';
        $availableDevelopmentLayers = implode(', ', $this->config->getAvailableDevelopmentLayers());

        $this->errorMessage = sprintf($template, $value, $availableDevelopmentLayers);
    }
}
