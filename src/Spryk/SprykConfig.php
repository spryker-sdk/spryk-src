<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk;

/**
 * @codeCoverageIgnore
 */
class SprykConfig
{
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
    public const SPRYK_DEFINITION_KEY_DESCRIPTION = 'description';

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
    public const NAME_ARGUMENT_KEY_DESCRIPTION = 'description';

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
     * @return array<string>
     */
    public function getCoreNamespaces(): array
    {
        /** @var string $namespacesFromEnv */
        $namespacesFromEnv = getenv('CORE_NAMESPACES');

        if ($namespacesFromEnv) {
            return explode(',', $namespacesFromEnv);
        }

        if (defined('SPRYKER_CORE_NAMESPACES')) {
            return explode(', ', SPRYKER_CORE_NAMESPACES);
        }

        return [];
    }

    /**
     * @return string|null
     */
    public function getProjectNamespace(): ?string
    {
        /** @var string $namespaceFromEnv */
        $namespaceFromEnv = getenv('PROJECT_NAMESPACE');

        if ($namespaceFromEnv) {
            return $namespaceFromEnv;
        }

        if (defined('SPRYKER_PROJECT_NAMESPACE')) {
            return SPRYKER_PROJECT_NAMESPACE;
        }

        return null;
    }

    /**
     * @return array<string>
     */
    public function getProjectNamespaces(): array
    {
        /** @var string $namespacesFromEnv */
        $namespacesFromEnv = getenv('SPRYKER_PROJECT_NAMESPACES');

        if ($namespacesFromEnv) {
            return explode(',', $namespacesFromEnv);
        }

        if (defined('SPRYKER_PROJECT_NAMESPACES')) {
            return explode(', ', SPRYKER_PROJECT_NAMESPACES);
        }

        return [];
    }

    /**
     * We need to be able to serve two cases:
     * 1. Building the cache file before the spryk.phar is created
     * 2. Building the cache file from project side
     *
     * First case ensures that we have a cache file in place OOTB for users of the spryk.phar.
     * Second case ensures that projects can build their own cache file when they have their own Spryk definitions.
     *
     * @return string
     */
    public function getArgumentListWritePath(): string
    {
        // 1. case try to use the var directory inside spryker-sdk/spryk-src.
        $generatedDirectory = $this->getSprykRootDirectory() . 'var' . DIRECTORY_SEPARATOR;

        // When projects executes the build command the var directory inside spryker-sdk/spryk is not writable
        // as it points to the root of the PHAR file.
        if (!file_exists($generatedDirectory) || !is_writable($generatedDirectory)) {
            // 2. case use path inside the project.
            $generatedDirectory = $this->getProjectRootDirectory() . static::NAME_DIRECTORY_GENERATED;
        }

        return $generatedDirectory . DIRECTORY_SEPARATOR . static::NAME_FILE_ARGUMENT_LIST;
    }

    /**
     * We need to be able to serve two cases:
     * 1. Reading the cache file from project (Spryks on project level)
     * 2. Reading the cache file from spryk.phar (no Spryks on project level)
     *
     * First case ensures that when a project has created its own cache this file will be used.
     * Second case ensures that when a project does not have their own cache the one from the spryk.phar will be used.
     *
     * @return string|null
     */
    public function getArgumentListReadPath(): ?string
    {
        // 1. case try to read from project.
        $projectCacheFilePath = $this->getProjectRootDirectory() . static::NAME_DIRECTORY_GENERATED . DIRECTORY_SEPARATOR . static::NAME_FILE_ARGUMENT_LIST;

        if (file_exists($projectCacheFilePath)) {
            return $projectCacheFilePath;
        }

        // 2. case try to read from spryk.phar.
        $sprykCacheFilePath = $this->getSprykRootDirectory() . 'var' . DIRECTORY_SEPARATOR . static::NAME_FILE_ARGUMENT_LIST;
        if (file_exists($sprykCacheFilePath)) {
            return $sprykCacheFilePath;
        }

        return null;
    }

    /**
     * Defines the default mode to use for the Spryks. Modes can be: project, core or both. When not set we use `project` as default.
     *
     * @return string
     */
    public function getDefaultMode(): string
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
