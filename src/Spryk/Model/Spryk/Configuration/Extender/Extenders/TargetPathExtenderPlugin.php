<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\Extenders;

use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderPluginInterface;
use SprykerSdk\Spryk\SprykConfig;

class TargetPathExtenderPlugin extends AbstractExtender implements SprykConfigurationExtenderPluginInterface
{
    /**
     * @param array $sprykConfig
     * @param array $context
     *
     * @return array
     */
    public function extend(array $sprykConfig, array $context): array
    {
        if ($this->isBoth($sprykConfig, $context)) {
            return $sprykConfig;
        }

        if ($this->isCore($sprykConfig, $context)) {
            return $sprykConfig;
        }

        return $this->buildProjectPath($sprykConfig);
    }

    protected function buildProjectPath(array $sprykConfig): array
    {
        $arguments = $this->getArguments($sprykConfig);

        if ($arguments === []) {
            return $sprykConfig;
        }

        if (!isset($arguments['targetPath'])) {
            return $sprykConfig;
        }

        $hasTargetPathDefault = isset($arguments['targetPath'][SprykConfig::NAME_ARGUMENT_KEY_DEFAULT]);

        $targetPath = $hasTargetPathDefault
            ? $arguments['targetPath'][SprykConfig::NAME_ARGUMENT_KEY_DEFAULT]
            : $arguments['targetPath'][SprykConfig::NAME_ARGUMENT_KEY_VALUE];

        $targetPath = $this->buildTargetPath($targetPath);

        if ($hasTargetPathDefault) {
            $arguments['targetPath'][SprykConfig::NAME_ARGUMENT_KEY_DEFAULT] = $targetPath;
        } else {
            $arguments['targetPath'][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $targetPath;
        }

        return $this->setArguments($arguments, $sprykConfig);
    }

    protected function buildTargetPath(string $targetPath): string
    {
        $pathPattern = sprintf('/\%1$ssrc\%1$s.+|\%1$stests\%1$s.+/', DIRECTORY_SEPARATOR);

        preg_match($pathPattern, $targetPath, $result);

        if ($result === []) {
            return $targetPath;
        }

        return ltrim(array_shift($result), DIRECTORY_SEPARATOR);
    }
}
