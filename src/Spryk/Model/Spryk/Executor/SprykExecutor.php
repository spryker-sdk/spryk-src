<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Executor;

use Jfcherng\Diff\DiffHelper;
use SprykerSdk\Spryk\Debug\DebugInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Collection\SprykBuilderCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\FileDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Cleanup\CleanupRunnerInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Builder\SprykDefinitionBuilderInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher\ConditionMatcherInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface;
use SprykerSdk\Spryk\ModeResolver\ModeResolverInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

class SprykExecutor implements SprykExecutorInterface
{
    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface>
     */
    protected array $executedSpryks = [];

    /**
     * @var array<string>
     */
    protected array $includeOptionalSubSpryks = [];

    /**
     * @var string
     */
    protected string $mainSprykDefinitionMode;

    /**
     * @var int
     */
    protected int $currentExecutionLevel = 0;

    /**
     * @var array<string, \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface>
     */
    protected array $postCommandCache = [];

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Command\SprykCommandInterface> $sprykCommands
     */
    public function __construct(
        private SprykDefinitionBuilderInterface $definitionBuilder,
        private SprykBuilderCollectionInterface $sprykBuilderCollection,
        private array $sprykCommands,
        private FileResolverInterface $fileResolver,
        private FileDumperInterface $fileDumper,
        private ConditionMatcherInterface $conditionMatcher,
        private CleanupRunnerInterface $cleanupRunner,
        private DebugInterface $debug,
        private ModeResolverInterface $modeResolver,
    ) {
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface $sprykExecutorConfiguration
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function execute(
        SprykExecutorConfigurationInterface $sprykExecutorConfiguration,
        SprykStyleInterface $style,
    ): void {
        $this->definitionBuilder->setStyle($style);
        $this->includeOptionalSubSpryks = $sprykExecutorConfiguration->getIncludeOptionalSubSpryks();

        $sprykPreDefinition = [];
        $sprykPreDefinition = $this->definitionBuilder->addTargetModuleParams(
            $sprykExecutorConfiguration,
            $sprykPreDefinition,
        );
        $sprykPreDefinition = $this->definitionBuilder->addDependentModuleParams(
            $sprykExecutorConfiguration,
            $sprykPreDefinition,
        );
        $sprykDefinition = $this->definitionBuilder->buildDefinition(
            $sprykExecutorConfiguration->getSprykName(),
            $sprykPreDefinition,
        );

        $this->mainSprykDefinitionMode = $this->modeResolver->getMode($sprykDefinition, $style);

        $this->debug->setStyle($style);
        // Execute nothing after debug information was printed
        if ($this->debug->isDebug()) {
            $this->debug->printDebug($sprykDefinition);

            return;
        }

        $this->buildSpryk($sprykDefinition, $style);

        $this->executePostCommands($style);

        $this->dumpFiles();
        $this->writeFiles($style);
        $this->cleanupRunner->runCleanup($style);
    }

    /**
     * Dumps all files to their location.
     */
    protected function dumpFiles(): void
    {
        $this->fileDumper->dumpFiles($this->fileResolver->all());
    }

    protected function writeFiles(SprykStyleInterface $style): void
    {
        $isDryRun = $style->getInput()->getOption('dry-run');

        foreach ($this->fileResolver->all() as $resolved) {
            if ($resolved->getOriginalContent() === $resolved->getContent()) {
                continue;
            }

            if ($isDryRun) {
                // Print diff to console
                $style->writeln($resolved->getFilePath());
                $style->writeln(DiffHelper::calculate($resolved->getOriginalContent(), $resolved->getContent()));

                continue;
            }

            $this->cleanupRunner->addForCleanup($resolved);

            file_put_contents($resolved->getFilePath(), $resolved->getContent());
        }
    }

    protected function buildSpryk(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        if (($sprykDefinition->getMode() !== 'both' && $sprykDefinition->getMode() !== $this->mainSprykDefinitionMode) || !$this->conditionMatched($sprykDefinition)) {
            return;
        }

        $style->startSpryk($sprykDefinition);

        $this->executePreCommands($sprykDefinition, $style);
        $this->executePreSpryks($sprykDefinition, $style);
        $this->executeSpryks($sprykDefinition, $style);
        $this->executeSpryk($sprykDefinition, $style);
        $this->executePostSpryks($sprykDefinition, $style);
        $this->cachePostCommands($sprykDefinition);

        $style->endSpryk($sprykDefinition);
    }

    protected function conditionMatched(SprykDefinitionInterface $sprykDefinition): bool
    {
        $conditionString = $sprykDefinition->getCondition();

        if (!$conditionString) {
            return true;
        }

        return $this->conditionMatcher->match($conditionString, $sprykDefinition->getArgumentCollection());
    }

    protected function executePreSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $style->startPreSpryks($sprykDefinition);
        ++$this->currentExecutionLevel;
        $this->buildPreSpryks($sprykDefinition, $style);
        --$this->currentExecutionLevel;
        $style->endPreSpryks($sprykDefinition);
    }

    protected function executeSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $style->startSpryks($sprykDefinition);
        ++$this->currentExecutionLevel;
        $this->buildSpryks($sprykDefinition, $style);
        --$this->currentExecutionLevel;
        $style->endSpryks($sprykDefinition);
    }

    protected function executeSpryk(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $builder = $this->sprykBuilderCollection->getBuilder($sprykDefinition);

        $style->writelnVerbose(sprintf('Starting: %s Level: %s', $sprykDefinition->getSprykName(), $this->currentExecutionLevel));

        $builder->runSpryk($sprykDefinition, $style);

        $style->writelnVerbose(sprintf('Finished: %s', $sprykDefinition->getSprykName()));

        $this->executedSpryks[$sprykDefinition->getSprykDefinitionKey()] = $sprykDefinition;
    }

    protected function executePostSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $style->startPostSpryks($sprykDefinition);
        ++$this->currentExecutionLevel;
        $this->buildPostSpryks($sprykDefinition, $style);
        --$this->currentExecutionLevel;
        $style->endPostSpryks($sprykDefinition);
    }

    protected function executePreCommands(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        if (!$sprykDefinition->getPreCommands()) {
            return;
        }

        $style->commandsEventReport('Pre commands start');

        foreach ($sprykDefinition->getPreCommands() as $preCommandName) {
            $this->executeCommand($preCommandName, $sprykDefinition, $style);
        }

        $style->commandsEventReport('Pre commands end');
    }

    /**
     * For performance reasons this method must be executed after all Spryks were executed.
     */
    protected function executePostCommands(SprykStyleInterface $style): void
    {
        $style->commandsEventReport('Post commands start');

        foreach ($this->postCommandCache as $postCommand => $sprykDefinition) {
            $this->executeCommand($postCommand, $sprykDefinition, $style);
        }

        $style->commandsEventReport('Post commands end');
    }

    protected function cachePostCommands(SprykDefinitionInterface $sprykDefinition): void
    {
        if (!$sprykDefinition->getPostCommands()) {
            return;
        }

        foreach ($sprykDefinition->getPostCommands() as $postCommand) {
            $this->postCommandCache[$postCommand] = $sprykDefinition;
        }
    }

    protected function executeCommand(
        string $commandName,
        SprykDefinitionInterface $sprykDefinition,
        SprykStyleInterface $style,
    ): void {
        foreach ($this->sprykCommands as $command) {
            if ($command->getName() !== $commandName) {
                continue;
            }

            $command->execute($sprykDefinition, $style);

            return;
        }
    }

    protected function buildPreSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $preSpryks = $sprykDefinition->getPreSpryks();
        $excludedSpryks = $sprykDefinition->getExcludedSpryks();

        if (count($preSpryks) === 0) {
            return;
        }

        foreach ($preSpryks as $preSprykDefinition) {
            if (isset($this->executedSpryks[$preSprykDefinition->getSprykDefinitionKey()])) {
                continue;
            }
            if (isset($excludedSpryks[$preSprykDefinition->getSprykName()])) {
                continue;
            }
            $this->buildSpryk($preSprykDefinition, $style);
        }
    }

    protected function buildSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $spryks = $sprykDefinition->getSpryks();
        $excludedSpryks = $sprykDefinition->getExcludedSpryks();

        if (count($spryks) === 0) {
            return;
        }

        foreach ($spryks as $sprykDefinition) {
            if (isset($this->executedSpryks[$sprykDefinition->getSprykDefinitionKey()])) {
                continue;
            }
            if (isset($excludedSpryks[$sprykDefinition->getSprykName()])) {
                continue;
            }
            $this->buildSpryk($sprykDefinition, $style);
        }
    }

    protected function buildPostSpryks(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): void
    {
        $postSpryks = $sprykDefinition->getPostSpryks();
        $excludedSpryks = $sprykDefinition->getExcludedSpryks();

        if (count($postSpryks) === 0) {
            return;
        }

        foreach ($postSpryks as $postSprykDefinition) {
            if (!$this->shouldSubSprykBeBuild($postSprykDefinition)) {
                continue;
            }
            if (isset($excludedSpryks[$postSprykDefinition->getSprykName()])) {
                continue;
            }
            $this->buildSpryk($postSprykDefinition, $style);
        }
    }

    protected function shouldSubSprykBeBuild(SprykDefinitionInterface $sprykDefinition): bool
    {
        if (isset($this->executedSpryks[$sprykDefinition->getSprykDefinitionKey()])) {
            return false;
        }

        if (isset($sprykDefinition->getConfig()['isOptional']) && !in_array($sprykDefinition->getSprykName(), $this->includeOptionalSubSpryks, true)) {
            return false;
        }

        return true;
    }
}
