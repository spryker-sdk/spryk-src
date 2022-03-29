<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk;

use Spryker\Shared\Config\Config;

/**
 * @codeCoverageIgnore
 */
class SprykConfig
{
    /**
     * @uses \Spryker\Shared\Kernel\KernelConstants::PROJECT_NAMESPACE
     *
     * @var string
     */
    protected const PROJECT_NAMESPACE = 'PROJECT_NAMESPACE';

    /**
     * @uses \Spryker\Shared\Kernel\KernelConstants::PROJECT_NAMESPACES
     *
     * @var string
     */
    protected const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';

    /**
     * @uses \Spryker\Shared\Kernel\KernelConstants::CORE_NAMESPACES
     *
     * @var string
     */
    protected const CORE_NAMESPACES = 'CORE_NAMESPACES';

    /**
     * @var string
     */
    public const SPRYK_DEFINITION_KEY_LEVEL = 'level';

    /**
     * @var string
     */
    public const SPRYK_DEFINITION_KEY_ARGUMENTS = 'arguments';

    /**
     * @var string
     */
    public const NAME_DEVELOPMENT_LAYER_CORE = 'core';

    /**
     * @var string
     */
    public const NAME_DEVELOPMENT_LAYER_PROJECT = 'project';

    /**
     * @var string
     */
    public const NAME_DEVELOPMENT_LAYER_BOTH = 'both';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_LAYER = 'layer';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_MODE = 'mode';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_KEY_DEFAULT = 'default';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_KEY_VALUE = 'value';

    /**
     * @var string
     */
    public const NAME_ARGUMENT_KEY_VALUES = 'values';

    /**
     * @var string
     */
    protected const NAME_DIRECTORY_GENERATED = 'generated';

    /**
     * @var string
     */
    protected const NAME_FILE_ARGUMENT_LIST = 'spryk_argument_list.yml';

    /**
     * @var string
     */
    protected const NAME_ORGANIZATION = 'spryker-sdk';

    /**
     * @var string
     */
    protected const NAME_PACKAGE = 'spryk';

    /**
     * @var int
     */
    protected const SPRYK_LEVEL_1 = 1;

    /**
     * @var int
     */
    protected const SPRYK_LEVEL_2 = 2;

    /**
     * @var int
     */
    protected const SPRYK_LEVEL_3 = 3;

    /**
     * @var int
     */
    public const SPRYK_DEFAULT_DUMP_LEVEL = self::SPRYK_LEVEL_1;

    /**
     * @return array<string>
     */
    public function getSprykDirectories(): array
    {
        return $this->buildDirectoryList('spryks');
    }

    /**
     * @return array<string>
     */
    public function getTemplateDirectories(): array
    {
        return $this->buildDirectoryList('templates');
    }

    /**
     * @param string|null $subDirectory
     *
     * @return array<string>
     */
    protected function buildDirectoryList(?string $subDirectory = null): array
    {
        $subDirectory = (is_string($subDirectory)) ? $subDirectory . DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR;

        $directories = [];

        // Path to Spryks on suite level
        $projectSprykDirectory = $this->getProjectRootDirectory() . 'config/spryk/' . $subDirectory;

        // Path to Spryks inside *this* package
        $sprykModuleDirectory = $this->getSprykRootDirectory() . 'config/spryk/' . $subDirectory;

        if (is_dir($projectSprykDirectory)) {
            $directories[] = $projectSprykDirectory . DIRECTORY_SEPARATOR;
        }

        if (is_dir($sprykModuleDirectory)) {
            $directories[] = $sprykModuleDirectory . DIRECTORY_SEPARATOR;
        }

        return $directories;
    }

    /**
     * The suite root directory.
     *
     * @return string
     */
    public function getProjectRootDirectory(): string
    {
        return rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * The `spryker-sdk/spryk` root directory.
     * When running as PHAR this points to the root inside of the spryk.phar.
     *
     * @return string
     */
    public function getSprykRootDirectory(): string
    {
        return rtrim(SPRYK_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return array
     */
    public function getAvailableDevelopmentLayers(): array
    {
        return [
            static::NAME_DEVELOPMENT_LAYER_CORE,
            static::NAME_DEVELOPMENT_LAYER_PROJECT,
            static::NAME_DEVELOPMENT_LAYER_BOTH,
        ];
    }

    /**
     * @return array<int>
     */
    public function getAvailableLevels(): array
    {
        return [
            static::SPRYK_LEVEL_1,
            static::SPRYK_LEVEL_2,
            static::SPRYK_LEVEL_3,
        ];
    }

    /**
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        $namespaces = [];
        /** @var string $namespacesFromEnv */
        $namespacesFromEnv = getenv('CORE_NAMESPACES');

        if ($namespacesFromEnv) {
            $namespaces = explode(',', $namespacesFromEnv);
        }

        return Config::get(
            static::CORE_NAMESPACES,
            $namespaces,
        );
    }

    /**
     * @return string|null
     */
    public function getProjectNamespace(): ?string
    {
        return Config::get(static::PROJECT_NAMESPACE, getenv('PROJECT_NAMESPACE') ?: '');
    }

    /**
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        $namespaces = [];

        /** @var string $namespacesFromEnv */
        $namespacesFromEnv = getenv('PROJECT_NAMESPACES');

        if ($namespacesFromEnv) {
            $namespaces = explode(',', $namespacesFromEnv);
        }

        return Config::get(
            static::PROJECT_NAMESPACES,
            $namespaces,
        );
    }

    /**
     * @return string
     */
    public function getArgumentListFilePath(): string
    {
        $generatedDirectory = $this->getSprykRootDirectory() . 'var' . DIRECTORY_SEPARATOR;

        if (!file_exists($generatedDirectory)) {
            $generatedDirectory = $this->getProjectRootDirectory() . static::NAME_DIRECTORY_GENERATED;
        }

        return $generatedDirectory . DIRECTORY_SEPARATOR . static::NAME_FILE_ARGUMENT_LIST;
    }

    /**
     * @return string
     */
    public function getDefaultDevelopmentMode(): string
    {
        return static::NAME_DEVELOPMENT_LAYER_PROJECT;
    }

    /**
     * @return int
     */
    public function getDefaultBuildLevel(): int
    {
        return static::SPRYK_LEVEL_3;
    }
}
