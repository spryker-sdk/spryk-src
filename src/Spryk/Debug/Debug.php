<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Debug;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher\ConditionMatcherInterface;
use SprykerSdk\Spryk\ModeResolver\ModeResolverInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;
use Symfony\Component\Console\Helper\Table;

class Debug implements DebugInterface
{
    /**
     * @var \SprykerSdk\Spryk\Style\SprykStyleInterface
     */
    private SprykStyleInterface $style;

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
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher\ConditionMatcherInterface $conditionMatcher
     * @param \SprykerSdk\Spryk\ModeResolver\ModeResolverInterface $modeResolver
     */
    public function __construct(private ConditionMatcherInterface $conditionMatcher, private ModeResolverInterface $modeResolver)
    {
    }

    /**
     * @retrun void
     *
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function setStyle(SprykStyleInterface $style): void
    {
        $this->style = $style;
    }

    /**
     * Standard Debug level, only Spryk names will be listed.
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        if ($this->style->getInput()->hasParameterOption('-dd')) {
            return true;
        }

        return $this->style->getInput()->hasParameterOption('-ddd');
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    public function printDebug(SprykDefinitionInterface $sprykDefinition): void
    {
        $this->mainSprykDefinitionMode = $this->modeResolver->getMode($sprykDefinition, $this->style);

        if (($sprykDefinition->getMode() !== 'both' && $sprykDefinition->getMode() !== $this->mainSprykDefinitionMode) || !$this->conditionMatched($sprykDefinition)) {
            return;
        }

        $this->buildSpryk($sprykDefinition);
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function buildSpryk(SprykDefinitionInterface $sprykDefinition): void
    {
        $this->executePreSpryks($sprykDefinition);
        $this->executeSpryks($sprykDefinition);
        $this->executeSpryk($sprykDefinition);
        $this->executePostSpryks($sprykDefinition);
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return bool
     */
    protected function conditionMatched(SprykDefinitionInterface $sprykDefinition): bool
    {
        $conditionString = $sprykDefinition->getCondition();

        if (!$conditionString) {
            return true;
        }

        return $this->conditionMatcher->match($conditionString, $sprykDefinition->getArgumentCollection());
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function executePreSpryks(SprykDefinitionInterface $sprykDefinition): void
    {
        ++$this->currentExecutionLevel;
        $this->buildPreSpryks($sprykDefinition);
        --$this->currentExecutionLevel;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function executeSpryks(SprykDefinitionInterface $sprykDefinition): void
    {
        ++$this->currentExecutionLevel;
        $this->buildSpryks($sprykDefinition);
        --$this->currentExecutionLevel;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function executeSpryk(SprykDefinitionInterface $sprykDefinition): void
    {
        $this->style->writeln(str_repeat('-', $this->currentExecutionLevel) . ' ' . $sprykDefinition->getSprykName());

        $table = new Table($this->style->getOutput());
        $table->setHeaders(['Argument', 'Value', 'Spryk']);

        foreach ($sprykDefinition->getArgumentCollection()->getArguments() as $argument) {
            $value = $this->getValueOfArgument($argument);
            $table->addRow([$argument->getName(), $value, $sprykDefinition->getSprykName()]);
        }

        $table->render();

        $this->executedSpryks[$sprykDefinition->getSprykDefinitionKey()] = $sprykDefinition;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface $argument
     *
     * @return mixed
     */
    private function getValueOfArgument(ArgumentInterface $argument): mixed
    {
        /** @var array|string|int|bool $value */
        $value = $argument->getValue();

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return implode("\n", $argument->getValue());
        }

        return $value;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function executePostSpryks(SprykDefinitionInterface $sprykDefinition): void
    {
        ++$this->currentExecutionLevel;
        $this->buildPostSpryks($sprykDefinition);
        --$this->currentExecutionLevel;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function buildPreSpryks(SprykDefinitionInterface $sprykDefinition): void
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
            $this->buildSpryk($preSprykDefinition);
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function buildSpryks(SprykDefinitionInterface $sprykDefinition): void
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
            $this->buildSpryk($sprykDefinition);
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    protected function buildPostSpryks(SprykDefinitionInterface $sprykDefinition): void
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
            $this->buildSpryk($postSprykDefinition);
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return bool
     */
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
