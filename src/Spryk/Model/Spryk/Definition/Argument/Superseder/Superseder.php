<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Superseder;

use SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\ArgumentInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\Resolver\CallbackArgumentResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class Superseder implements SupersederInterface
{
    /**
     * @var string
     */
    public const PLACEHOLDER_PATTERN = '/{{(.*?)[}|\|]/';

    /**
     * @var array<string>
     */
    protected array $resolvedArguments = [];

    public function __construct(protected TemplateRendererInterface $templateRenderer, protected CallbackArgumentResolverInterface $callbackArgumentResolver)
    {
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $sprykArguments
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $resolvedArguments
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface
     */
    public function supersede(ArgumentCollectionInterface $sprykArguments, ArgumentCollectionInterface $resolvedArguments): ArgumentCollectionInterface
    {
        foreach ($sprykArguments->getArguments() as $argument) {
            if ($argument->getValue() === null) {
                $this->callbackArgumentResolver->resolveArgument($argument, $sprykArguments);

                continue;
            }
            $this->resolveArgument($argument, $sprykArguments, $resolvedArguments);

            // Need to resolve callbacks after value was received. Other arguments could need already the completely resolved value.
            $this->callbackArgumentResolver->resolveArgument($argument, $sprykArguments);
        }

        return $sprykArguments;
    }

    protected function resolveArgument(
        ArgumentInterface $argument,
        ArgumentCollectionInterface $sprykArguments,
        ArgumentCollectionInterface $resolvedArguments,
    ): void {
        $argumentValue = $argument->getValue();

        if (!is_array($argumentValue)) {
            $argument->setValue(
                $this->replacePlaceholderInValue($argumentValue, $sprykArguments, $resolvedArguments),
            );

            return;
        }

        $argumentValues = [];

        foreach ($argumentValue as $value) {
            $argumentValues[] = $this->replacePlaceholderInValue($value, $sprykArguments, $resolvedArguments);
        }

        $argument->setValue($argumentValues);
    }

    protected function replacePlaceholderInValue(
        mixed $argumentValue,
        ArgumentCollectionInterface $sprykArguments,
        ArgumentCollectionInterface $resolvedArguments,
    ): mixed {
        if (is_bool($argumentValue) || is_int($argumentValue)) {
            return $argumentValue;
        }

        // In case we have a condition like {% if .... %} we need to resolve this one differently
//        if (is_string($argumentValue) && strpos('{%', $argumentValue) !== false) {
//            return $this->replacePlaceholderInValueWithCondition($argumentValue, $sprykArguments, $resolvedArguments);
//        }

        preg_match_all(static::PLACEHOLDER_PATTERN, $argumentValue, $matches, PREG_SET_ORDER);

        if (count($matches) === 0) {
            return $argumentValue;
        }

        $replacements = [];
        foreach ($matches as $match) {
            $argumentName = trim($match[1]);
            $resolvedArgumentValue = $this->getResolvedValue($argumentName, $sprykArguments, $resolvedArguments);
            $replacements[$argumentName] = $resolvedArgumentValue;
        }

        $replacements = array_merge($replacements, $sprykArguments->getArguments());

        return $this->templateRenderer->renderString($argumentValue, $replacements, $sprykArguments->getSprykName());
    }

    /**
     * The argument value contains a condition like {% if .... %}. We first need to get the values from the collection resolved
     * which are not yet resolved. After that we can use Twig to check if the condition is matched and resolve the condition.
     */
    protected function replacePlaceholderInValueWithCondition(
        string $argumentValue,
        ArgumentCollectionInterface $sprykArguments,
        ArgumentCollectionInterface $resolvedArguments,
    ): string {
        $replacements = [];

        foreach ($resolvedArguments->getArguments() as $resolvedArgument) {
            $value = $resolvedArgument->getValue();
            if ($value === 'default' || (is_string($value) && strpos('{{', $value) !== false)) {
                $value = $this->getResolvedValue($resolvedArgument->getName(), $sprykArguments, $resolvedArguments);
            }
            $replacements[$resolvedArgument->getName()] = $value;
        }

        return $this->templateRenderer->renderString($argumentValue, $replacements, $sprykArguments->getSprykName());
    }

    /**
     * @return mixed
     */
    protected function getResolvedValue(string $argumentName, ArgumentCollectionInterface $sprykArguments, ArgumentCollectionInterface $resolvedArguments)
    {
        $argument = $resolvedArguments->getArgument($argumentName);

        if ($sprykArguments->hasArgument($argumentName)) {
            $argument = $sprykArguments->getArgument($argumentName);
        }

        return $this->resolveValue($argument, $sprykArguments, $resolvedArguments);
    }

    /**
     * @return mixed
     */
    protected function resolveValue(ArgumentInterface $argument, ArgumentCollectionInterface $sprykArguments, ArgumentCollectionInterface $resolvedArguments)
    {
        $value = $argument->getValue();

        if ($value !== null && $this->argumentHasPlaceholder($value)) {
            $this->resolveArgument($argument, $sprykArguments, $resolvedArguments);
        }

        return $argument->getValue();
    }

    protected function argumentHasPlaceholder(string $argumentValue): bool
    {
        preg_match_all(static::PLACEHOLDER_PATTERN, $argumentValue, $matches, PREG_SET_ORDER);

        if (count($matches) === 0) {
            return false;
        }

        return true;
    }
}
