<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Configuration\Loader;

use SprykerSdk\Spryk\Exception\SprykConfigNotValid;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Finder\SprykConfigurationFinderInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Validator\ConfigurationValidatorInterface;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Yaml\Yaml;

class SprykConfigurationLoader implements SprykConfigurationLoaderInterface
{
    /**
     * @var array<string, mixed>|null
     */
    protected ?array $rootConfiguration = null;

    /**
     * @var array<string, array>
     */
    protected array $loadedConfigurations = [];

    public function __construct(
        protected SprykConfigurationFinderInterface $configurationFinder,
        protected SprykConfigurationExtenderInterface $configurationExtender,
        protected ConfigurationValidatorInterface $configurationValidator,
        protected SprykConfig $sprykConfig,
    ) {
    }

    /**
     * @param string $sprykName
     * @param string|null $sprykMode
     *
     * @return array
     */
    public function loadSpryk(string $sprykName, ?string $sprykMode = null): array
    {
        $sprykConfiguration = $this->load($sprykName);

        $sprykConfiguration = $this->buildMode($sprykConfiguration, $sprykMode);
        $sprykConfiguration = $this->buildLevel($sprykConfiguration);

        return $this->configurationExtender->extend($sprykConfiguration, [
            'sprykName' => $sprykName,
        ]);
    }

    protected function load(string $sprykName): array
    {
        if (!isset($this->loadedConfigurations[$sprykName])) {
            $sprykConfiguration = $this->configurationFinder->find($sprykName);
            $this->loadedConfigurations[$sprykName] = Yaml::parse($sprykConfiguration->getContents());
        }

        return $this->loadedConfigurations[$sprykName];
    }

    protected function getRootConfiguration(): array
    {
        if (!$this->rootConfiguration) {
            $rootConfiguration = $this->configurationFinder->find('spryk');
            $this->rootConfiguration = Yaml::parse($rootConfiguration->getContents());
        }

        return $this->rootConfiguration;
    }

    /**
     * @throws \SprykerSdk\Spryk\Exception\SprykConfigNotValid
     */
    protected function validateConfiguration(array $sprykConfiguration): void
    {
        $validationErrorMessages = $this->configurationValidator->validate($sprykConfiguration);

        if ($validationErrorMessages === []) {
            return;
        }

        throw new SprykConfigNotValid(implode(PHP_EOL, $validationErrorMessages));
    }

    protected function buildMode(array $sprykConfiguration, ?string $sprykMode = null): array
    {
        if (!isset($sprykConfiguration[SprykConfig::NAME_ARGUMENT_MODE])) {
            $sprykConfiguration[SprykConfig::NAME_ARGUMENT_MODE] = $this->sprykConfig->getDefaultMode();
        }

        if ($sprykMode !== null && $sprykConfiguration[SprykConfig::NAME_ARGUMENT_MODE] === 'both') {
            $sprykConfiguration[SprykConfig::NAME_ARGUMENT_MODE] = $sprykMode;
        }

        return $sprykConfiguration;
    }

    protected function buildLevel(array $sprykConfiguration): array
    {
        if (!isset($sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_LEVEL])) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_LEVEL] = $this->sprykConfig->getDefaultBuildLevel();
        }

        return $sprykConfiguration;
    }
}
