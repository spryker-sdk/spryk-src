<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Builder;

use SprykerSdk\Spryk\Debug\DebugInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Argument;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\ArgumentResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface;
use SprykerSdk\Spryk\SprykConfig;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

class SprykDefinitionBuilder implements SprykDefinitionBuilderInterface
{
    /**
     * @var string
     */
    protected const SPRYK_BUILDER_NAME = 'spryk';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_PRE_SPRYKS = 'preSpryks';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_SPRYKS = 'spryks';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_EXCLUDED_SPRYKS = 'excludedSpryks';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_POST_SPRYKS = 'postSpryks';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_PRE_COMMANDS = 'preCommands';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_POST_COMMANDS = 'postCommands';

    /**
     * @var string
     */
    protected const CONFIGURATION_KEY_CONDITION = 'condition';

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderInterface
     */
    protected SprykConfigurationExtenderInterface $configurationExtender;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface
     */
    protected SprykConfigurationLoaderInterface $sprykLoader;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\ArgumentResolverInterface
     */
    protected ArgumentResolverInterface $argumentResolver;

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface>
     */
    protected array $definitionCollection = [];

    /**
     * @var array<mixed>
     */
    protected array $debugData = [];

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface>
     */
    protected array $argumentCollectionCache = [];

    /**
     * @var string|null
     */
    protected ?string $calledSpryk = null;

    /**
     * @var \SprykerSdk\Spryk\Style\SprykStyleInterface
     */
    protected SprykStyleInterface $style;

    /**
     * @var \SprykerSdk\Spryk\Debug\DebugInterface
     */
    protected DebugInterface $debug;

    /**
     * @var string|null
     */
    protected ?string $mode = null;

    /**
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected $sprykConfig;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderInterface $configurationExtender
     * @param \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface $sprykLoader
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\ArgumentResolverInterface $argumentResolver
     * @param \SprykerSdk\Spryk\SprykConfig $sprykConfig
     */
    public function __construct(
        SprykConfigurationExtenderInterface $configurationExtender,
        SprykConfigurationLoaderInterface $sprykLoader,
        ArgumentResolverInterface $argumentResolver,
        SprykConfig $sprykConfig,
    ) {
        $this->configurationExtender = $configurationExtender;
        $this->sprykLoader = $sprykLoader;
        $this->argumentResolver = $argumentResolver;
        $this->sprykConfig = $sprykConfig;
    }

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return $this
     */
    public function setStyle(SprykStyleInterface $style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @param \SprykerSdk\Spryk\Debug\DebugInterface $debug
     *
     * @return $this
     */
    public function setDebug(DebugInterface $debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @param string $sprykName
     * @param array|null $preDefinedDefinition
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface|null $parentArgumentCollection
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface
     */
    public function buildDefinition(
        string $sprykName,
        ?array $preDefinedDefinition = null,
        ?ArgumentCollectionInterface $parentArgumentCollection = null,
    ): SprykDefinitionInterface {
        $this->debugData['countAll'] = isset($this->debugData['countAll']) ? ++$this->debugData['countAll'] : 1;
        $this->debugData['countAdded'] ??= 0;

        if ($this->calledSpryk === null) {
            $this->calledSpryk = $sprykName;
        }
        $sprykConfiguration = $this->loadConfig($sprykName);

        $mode = $this->getMode($sprykConfiguration);
        $organization = $this->getOrganization($mode);

        if (isset($preDefinedDefinition['excludedSpryks']) && !empty($preDefinedDefinition['excludedSpryks'])) {
            $sprykConfiguration['excludedSpryks'] = $preDefinedDefinition['excludedSpryks'];
        }

        $arguments = $this->mergeArguments($sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS], $preDefinedDefinition);

        $context = [
            'mode' => $mode,
            'organization' => $mode,
            'sprykName' => $sprykName,
        ];

        $arguments = $this->configurationExtender->extend($arguments, $context);

        if ($organization && isset($arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION])) {
            unset($arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION]['default'], $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION]['values']);
            $arguments[SprykConfig::NAME_ARGUMENT_ORGANIZATION]['value'] = $organization;
        }

        // The Argument Collection will contain all Spryk argument collections. The last one is the first entry and
        // each entry has a `previousSprykArgumentCollection` containing the previously resolved arguments.
        $argumentCollection = $this->argumentResolver->resolve(
            $arguments,
            $sprykName,
            $this->style,
            $parentArgumentCollection,
        );

        $sprykDefinitionKey = sprintf('%s.%s', $sprykName, $argumentCollection->getFingerprint());
        $this->argumentCollectionCache[$sprykDefinitionKey] = $argumentCollection;

        // Some Spryks don't have the need for an application argument but for the debug information we need to have an application.
        if (!$argumentCollection->hasArgument('application')) {
            $applicationArgument = new Argument();
            $applicationArgument->setName('application')
                ->setValue('default');

            $argumentCollection->addArgument($applicationArgument);
        }

        if (!isset($this->definitionCollection[$sprykDefinitionKey])) {
            // Collect debug information
            $count = $this->debugData[$argumentCollection->getArgument('organization')->getValue()][$argumentCollection->getArgument('module')->getValue()][$argumentCollection->getArgument('application')->getValue()][$argumentCollection->getSprykName()] ?? 0;

            $this->debugData[$argumentCollection->getArgument('organization')->getValue()][$argumentCollection->getArgument('module')->getValue()][$argumentCollection->getArgument('application')->getValue()][$argumentCollection->getSprykName()] = ++$count;

            $this->debugData['countAdded'] = ++$this->debugData['countAdded'];

            $sprykDefinition = $this->createDefinition($sprykName, $sprykConfiguration[static::SPRYK_BUILDER_NAME]);

            $this->definitionCollection[$sprykDefinitionKey] = $sprykDefinition;

            $sprykDefinition
                ->setMode($this->getMode($sprykConfiguration))
                ->setArgumentCollection($argumentCollection)
                ->setSprykDefinitionKey($sprykDefinitionKey)
                ->setPreCommands($this->getPreCommands($sprykConfiguration))
                ->setExcludedSpryks($this->getExcludedSpryks($sprykConfiguration))
                ->setPreSpryks($this->getPreSpryks($sprykConfiguration, $sprykDefinitionKey))
                ->setSpryks($this->getSpryks($sprykConfiguration, $sprykDefinitionKey))
                ->setPostSpryks($this->getPostSpryks($sprykConfiguration, $sprykDefinitionKey))
                ->setPostCommands($this->getPostCommands($sprykConfiguration))
                ->setConfig($this->getConfig($sprykConfiguration, $preDefinedDefinition))
                ->setCondition($this->getCondition($sprykConfiguration, $preDefinedDefinition));
        }

        return $this->definitionCollection[$sprykDefinitionKey];
    }

    /**
     * Returns either the by User selected mode or the default mode.
     *
     * @param array $sprykDefinition
     *
     * @return string
     */
    protected function getMode(array $sprykDefinition): string
    {
        if (isset($sprykDefinition[SprykConfig::NAME_ARGUMENT_MODE]) && $sprykDefinition[SprykConfig::NAME_ARGUMENT_MODE] === SprykConfig::NAME_DEVELOPMENT_LAYER_CORE) {
            return SprykConfig::NAME_DEVELOPMENT_LAYER_CORE;
        }

        $mode = $this->style->getInput()->getOption(SprykConfig::NAME_ARGUMENT_MODE);

        if (!$mode) {
            return $this->sprykConfig->getDefaultMode();
        }

        return $mode;
    }

    /**
     * Returns either the by User selected organization, the project organization or null.
     *
     * @param string $mode
     *
     * @return string|null
     */
    protected function getOrganization(string $mode): ?string
    {
        $organization = $this->style->getInput()->getOption(SprykConfig::NAME_ARGUMENT_ORGANIZATION);

        if (!$organization && $mode === SprykConfig::NAME_DEVELOPMENT_LAYER_PROJECT) {
            return $this->sprykConfig->getProjectNamespace();
        }

        return $organization;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface $sprykExecutorConfiguration
     * @param array<mixed> $sprykConfiguration
     *
     * @return array<mixed>
     */
    public function addTargetModuleParams(
        SprykExecutorConfigurationInterface $sprykExecutorConfiguration,
        array $sprykConfiguration,
    ): array {
        if ($sprykExecutorConfiguration->getTargetModule()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]['module'][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getTargetModule();
        }

        if ($sprykExecutorConfiguration->getTargetModuleOrganization()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS][SprykConfig::NAME_ARGUMENT_ORGANIZATION][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getTargetModuleOrganization();
        }

        if ($sprykExecutorConfiguration->getTargetModuleLayer()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS][SprykConfig::NAME_ARGUMENT_LAYER][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getTargetModuleLayer();
        }

        return $sprykConfiguration;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface $sprykExecutorConfiguration
     * @param array<mixed> $sprykConfiguration
     *
     * @return array<mixed>
     */
    public function addDependentModuleParams(
        SprykExecutorConfigurationInterface $sprykExecutorConfiguration,
        array $sprykConfiguration,
    ): array {
        if ($sprykExecutorConfiguration->getDependentModule()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]['dependentModule'][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getDependentModule();
        }

        if ($sprykExecutorConfiguration->getDependentModuleOrganization()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]['dependentModuleOrganization'][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getDependentModuleOrganization();
        }

        if ($sprykExecutorConfiguration->getDependentModuleLayer()) {
            $sprykConfiguration[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]['dependentModuleLayer'][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $sprykExecutorConfiguration->getDependentModuleLayer();
        }

        return $sprykConfiguration;
    }

    /**
     * @param array $arguments
     * @param array|null $preDefinedDefinition
     *
     * @return array
     */
    protected function mergeArguments(array $arguments, ?array $preDefinedDefinition = null): array
    {
        if (is_array($preDefinedDefinition) && isset($preDefinedDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS])) {
            $arguments = array_merge($preDefinedDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS], $arguments);
            $arguments = array_replace_recursive($arguments, $preDefinedDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]);
        }

        return $arguments;
    }

    /**
     * @param string $sprykName
     * @param string $builderName
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface
     */
    protected function createDefinition(string $sprykName, string $builderName): SprykDefinitionInterface
    {
        $sprykDefinition = new SprykDefinition();
        $sprykDefinition
            ->setBuilder($builderName)
            ->setSprykName($sprykName);

        return $sprykDefinition;
    }

    /**
     * @param array $sprykConfiguration
     * @param array|null $preDefinedConfiguration
     *
     * @return array
     */
    protected function getConfig(array $sprykConfiguration, ?array $preDefinedConfiguration = null): array
    {
        $configuration = [];

        if (isset($sprykConfiguration['config'])) {
            $configuration = $sprykConfiguration['config'];
        }

        if (is_array($preDefinedConfiguration)) {
            return array_merge($configuration, $preDefinedConfiguration);
        }

        return $configuration;
    }

    /**
     * @param array $sprykConfiguration
     * @param array|null $preDefinedDefinition
     *
     * @return string
     */
    protected function getCondition(array $sprykConfiguration, ?array $preDefinedDefinition = null): string
    {
        if (isset($sprykConfiguration[static::CONFIGURATION_KEY_CONDITION])) {
            return $sprykConfiguration[static::CONFIGURATION_KEY_CONDITION];
        }

        if (isset($preDefinedDefinition[static::CONFIGURATION_KEY_CONDITION])) {
            return $preDefinedDefinition[static::CONFIGURATION_KEY_CONDITION];
        }

        return '';
    }

    /**
     * @param array $sprykConfiguration
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected function getExcludedSpryks(array $sprykConfiguration): array
    {
        $excludedSpryks = [];

        if (isset($sprykConfiguration[static::CONFIGURATION_KEY_EXCLUDED_SPRYKS])) {
            foreach ($sprykConfiguration[static::CONFIGURATION_KEY_EXCLUDED_SPRYKS] as $sprykName) {
                $excludedSpryks[$sprykName] = true;
            }
        }

        /** @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition> $excludedSpryks */
        $excludedSpryks = array_filter($excludedSpryks);

        return $excludedSpryks;
    }

    /**
     * @param array $sprykConfiguration
     * @param string $parentSprykDefinitionKey
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected function getPreSpryks(array $sprykConfiguration, string $parentSprykDefinitionKey): array
    {
        $preSpryks = [];
        if (isset($sprykConfiguration[static::CONFIGURATION_KEY_PRE_SPRYKS])) {
            $preSpryks = $this->buildPreSprykDefinitions(
                $sprykConfiguration[static::CONFIGURATION_KEY_PRE_SPRYKS],
                $parentSprykDefinitionKey,
            );
        }

        return array_filter($preSpryks);
    }

    /**
     * @param array $preSpryks
     * @param string $parentSprykDefinitionKey
     *
     * @return array
     */
    protected function buildPreSprykDefinitions(array $preSpryks, string $parentSprykDefinitionKey): array
    {
        $preSprykDefinitions = [];
        foreach ($preSpryks as $preSprykName) {
            $preSprykDefinitions[] = $this->buildSubSprykDefinition($preSprykName, $parentSprykDefinitionKey);
        }

        return $preSprykDefinitions;
    }

    /**
     * @param array $sprykConfiguration
     * @param string $parentSprykDefinitionKey
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected function getSpryks(array $sprykConfiguration, string $parentSprykDefinitionKey): array
    {
        $spryks = [];
        if (isset($sprykConfiguration[static::CONFIGURATION_KEY_SPRYKS])) {
            $spryks = $this->buildSprykDefinitions(
                $sprykConfiguration[static::CONFIGURATION_KEY_SPRYKS],
                $parentSprykDefinitionKey,
            );
        }

        return array_filter($spryks);
    }

    /**
     * @param array $spryks
     * @param string $parentSprykDefinitionKey
     *
     * @return array
     */
    protected function buildSprykDefinitions(array $spryks, string $parentSprykDefinitionKey): array
    {
        $sprykDefinitions = [];
        foreach ($spryks as $sprykName) {
            $sprykDefinitions[] = $this->buildSubSprykDefinition($sprykName, $parentSprykDefinitionKey);
        }

        return $sprykDefinitions;
    }

    /**
     * @param array $sprykConfiguration
     * @param string $parentSprykDefinitionKey
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected function getPostSpryks(array $sprykConfiguration, string $parentSprykDefinitionKey): array
    {
        $postSpryks = [];
        if (isset($sprykConfiguration[static::CONFIGURATION_KEY_POST_SPRYKS])) {
            $postSpryks = $this->buildPostSprykDefinitions(
                $sprykConfiguration[static::CONFIGURATION_KEY_POST_SPRYKS],
                $parentSprykDefinitionKey,
            );
        }

        return array_filter($postSpryks);
    }

    /**
     * @param array $postSpryks
     * @param string $parentSprykDefinitionKey
     *
     * @return array
     */
    protected function buildPostSprykDefinitions(array $postSpryks, string $parentSprykDefinitionKey): array
    {
        $postSprykDefinitions = [];
        foreach ($postSpryks as $postSprykName) {
            $postSprykDefinitions[] = $this->buildSubSprykDefinition($postSprykName, $parentSprykDefinitionKey);
        }

        return $postSprykDefinitions;
    }

    /**
     * @param mixed $sprykInfo
     * @param string $parentSprykDefinitionKey
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface|null
     */
    protected function buildSubSprykDefinition($sprykInfo, string $parentSprykDefinitionKey): ?SprykDefinitionInterface
    {
        $parentArgumentCollection = clone $this->argumentCollectionCache[$parentSprykDefinitionKey];

        if (!is_array($sprykInfo)) {
            return $this->buildDefinition($sprykInfo, [], $parentArgumentCollection);
        }

        $sprykName = array_keys($sprykInfo)[0];
        $preDefinedDefinition = $sprykInfo[$sprykName];

        return $this->buildDefinition($sprykName, $preDefinedDefinition, $parentArgumentCollection);
    }

    /**
     * @param array<mixed> $sprykConfiguration
     *
     * @return array<string>
     */
    protected function getPreCommands(array $sprykConfiguration): array
    {
        if (!isset($sprykConfiguration[static::CONFIGURATION_KEY_PRE_COMMANDS])) {
            return [];
        }

        return $sprykConfiguration[static::CONFIGURATION_KEY_PRE_COMMANDS];
    }

    /**
     * @param array<mixed> $sprykConfiguration
     *
     * @return array<string>
     */
    protected function getPostCommands(array $sprykConfiguration): array
    {
        if (!isset($sprykConfiguration[static::CONFIGURATION_KEY_POST_COMMANDS])) {
            return [];
        }

        return $sprykConfiguration[static::CONFIGURATION_KEY_POST_COMMANDS];
    }

    /**
     * @param string $sprykName
     *
     * @return array
     */
    protected function loadConfig(string $sprykName): array
    {
        return $this->sprykLoader->loadSpryk($sprykName);
    }
}
