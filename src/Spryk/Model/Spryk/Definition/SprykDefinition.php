<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class SprykDefinition implements SprykDefinitionInterface
{
    /**
     * @var string
     */
    protected string $builder;

    /**
     * @var string
     */
    protected string $sprykName;

    /**
     * @var string
     */
    protected string $sprykDefinitionKey;

    /**
     * @var string
     */
    protected string $description;

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface
     */
    protected ArgumentCollectionInterface $argumentCollection;

    /**
     * @var bool
     */
    protected bool $isCalled = false;

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected array $excludedSpryks = [];

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected array $preSpryks = [];

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected array $spryks = [];

    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    protected array $postSpryks = [];

    /**
     * @var array<string>
     */
    protected array $preCommands = [];

    /**
     * @var array<string>
     */
    protected array $postCommands = [];

    /**
     * @var string
     */
    protected string $mode;

    /**
     * @var string
     */
    protected string $condition;

    /**
     * @return string
     */
    public function getBuilder(): string
    {
        return $this->builder;
    }

    /**
     * @param string $builder
     *
     * @return $this
     */
    public function setBuilder(string $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return string
     */
    public function getSprykName(): string
    {
        return $this->sprykName;
    }

    /**
     * @param string $sprykName
     *
     * @return $this
     */
    public function setSprykName(string $sprykName)
    {
        $this->sprykName = $sprykName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSprykDefinitionKey(): string
    {
        return $this->sprykDefinitionKey;
    }

    /**
     * @param string $sprykDefinitionKey
     *
     * @return $this
     */
    public function setSprykDefinitionKey(string $sprykDefinitionKey)
    {
        $this->sprykDefinitionKey = $sprykDefinitionKey;

        return $this;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface
     */
    public function getArgumentCollection(): ArgumentCollectionInterface
    {
        return $this->argumentCollection;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     *
     * @return $this
     */
    public function setArgumentCollection(ArgumentCollectionInterface $argumentCollection)
    {
        $this->argumentCollection = $argumentCollection;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    public function getExcludedSpryks(): array
    {
        return $this->excludedSpryks;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition> $excludedSpryks
     *
     * @return $this
     */
    public function setExcludedSpryks(array $excludedSpryks)
    {
        $this->excludedSpryks = $excludedSpryks;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    public function getPreSpryks(): array
    {
        return $this->preSpryks;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition> $preSpryks
     *
     * @return $this
     */
    public function setPreSpryks(array $preSpryks)
    {
        $this->preSpryks = $preSpryks;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    public function getSpryks(): array
    {
        return $this->spryks;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition> $spryks
     *
     * @return $this
     */
    public function setSpryks(array $spryks)
    {
        $this->spryks = $spryks;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition>
     */
    public function getPostSpryks(): array
    {
        return $this->postSpryks;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinition> $postSpryks
     *
     * @return $this
     */
    public function setPostSpryks(array $postSpryks)
    {
        $this->postSpryks = $postSpryks;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getPreCommands(): array
    {
        return $this->preCommands;
    }

    /**
     * @param array<string> $preCommands
     *
     * @return $this
     */
    public function setPreCommands(array $preCommands)
    {
        $this->preCommands = $preCommands;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getPostCommands(): array
    {
        return $this->postCommands;
    }

    /**
     * @param array<string> $postCommands
     *
     * @return $this
     */
    public function setPostCommands(array $postCommands)
    {
        $this->postCommands = $postCommands;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function setCondition(string $condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }
}
