<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection;

use SprykerSdk\Spryk\Exception\ArgumentNotFoundException;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface;
use SprykerSdk\Spryk\SprykConfig;

class ArgumentCollection implements ArgumentCollectionInterface
{
    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface>
     */
    protected array $arguments = [];

    /**
     * @var string
     */
    protected string $sprykName;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface|null
     */
    protected ?ArgumentCollectionInterface $previousSprykArgumentCollection = null;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface $argument
     *
     * @return $this
     */
    public function addArgument(ArgumentInterface $argument)
    {
        // Do not override the first found argument.
        if (isset($this->arguments[$argument->getName()])) {
            return $this;
        }

        $this->arguments[$argument->getName()] = $argument;

        return $this;
    }

    /**
     * @param string $name
     * @param bool $lookupPreviousSprykArgumentCollection When set to true we try to resolve from previous Spryk definitions.
     *
     * @return bool
     */
    public function hasArgument(string $name, bool $lookupPreviousSprykArgumentCollection = false): bool
    {
        if (isset($this->arguments[$name])) {
            return true;
        }

        if ($lookupPreviousSprykArgumentCollection && $this->previousSprykArgumentCollection !== null && $this->previousSprykArgumentCollection->hasArgument($name)) {
            return true;
        }

        return false;
    }

    /**
     * Method first look up in to the current Spryk known arguments, if it has it will return this otherwise it bubbles up to the previous one until it finds one.
     *
     * @param string $name
     * @param bool $lookupPreviousSprykArgumentCollection When set to true we try to resolve from previous Spryk definitions.
     *
     * @throws \SprykerSdk\Spryk\Exception\ArgumentNotFoundException
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface
     */
    public function getArgument(string $name, bool $lookupPreviousSprykArgumentCollection = false): ArgumentInterface
    {
        if (!$this->hasArgument($name, $lookupPreviousSprykArgumentCollection)) {
            throw new ArgumentNotFoundException(sprintf('Argument "%s" could not be found. Maybe there is a typo in your "%s" definition.', $name, $this->getSprykName()));
        }

        if (isset($this->arguments[$name])) {
            return $this->arguments[$name];
        }

        if ($lookupPreviousSprykArgumentCollection && $this->previousSprykArgumentCollection instanceof ArgumentCollectionInterface) {
            return $this->previousSprykArgumentCollection->getArgument($name);
        }

        throw new ArgumentNotFoundException(sprintf('Argument "%s" could not be found. Maybe there is a typo in your "%s" definition.', $name, $this->getSprykName()));
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getArgumentsAsArray(): array
    {
        $result = [];

        foreach ($this->arguments as $argument) {
            $result[$argument->getName()][SprykConfig::NAME_ARGUMENT_KEY_VALUE] = $argument->getValue();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getFingerprint(): string
    {
        $argumentsArray = $this->getArgumentsAsArray();
        ksort($argumentsArray);

        return sha1((string)json_encode($argumentsArray));
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
    public function getSprykName(): string
    {
        return $this->sprykName;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     *
     * @return $this
     */
    public function setPreviousSprykArguments(ArgumentCollectionInterface $argumentCollection)
    {
        $this->previousSprykArgumentCollection = $argumentCollection;

        return $this;
    }
}
