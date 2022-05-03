<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Plugin;

use PhpParser\NodeTraverser;
use SprykerSdk\Spryk\Exception\SprykException;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddPluginToPluginListVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddUseVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;

class WirePluginSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    protected const SPRYK_NAME = 'wirePlugin';

    /**
     * @var string
     */
    public const ARGUMENT_TARGET = 'target';

    /**
     * @var string
     */
    public const ARGUMENT_PLUGIN = 'plugin';

    /**
     * @var string
     */
    public const ARGUMENT_ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const ARGUMENT_PLUGIN_INDEX = 'pluginIndex';

    /**
     * @var string
     */
    public const ARGUMENT_PLUGIN_BEFORE = 'pluginBefore';

    /**
     * @var string
     */
    public const ARGUMENT_PLUGIN_AFTER = 'pluginAfter';

    /**
     * @return void
     */
    protected function build(): void
    {
        $targetClassName = $this->getTargetClassName();
        $resolvedTargetClass = $this->resolveTargetClass($targetClassName);

        $targetMethodName = $this->getTargetMethodName();
        $pluginClassNames = $this->getPluginClassNames();

        foreach ($pluginClassNames as $pluginClassName) {
            $traverser = new NodeTraverser();
            $traverser->addVisitor(new AddUseVisitor($pluginClassName));
            $traverser->addVisitor(
                new AddPluginToPluginListVisitor(
                    $targetMethodName,
                    $pluginClassName,
                    $this->getPluginBefore(),
                    $this->getPluginAfter(),
                    $this->getPluginIndex(),
                ),
            );
            $newStmts = $traverser->traverse($resolvedTargetClass->getClassTokenTree());
            $resolvedTargetClass->setClassTokenTree($newStmts);

            $this->log(sprintf('Added plugin "%s" to "%s::%s()"', $pluginClassName, $targetClassName, $targetMethodName));
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYK_NAME;
    }

    /**
     * @param string $targetClassName
     *
     * @throws \SprykerSdk\Spryk\Exception\SprykException
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface
     */
    protected function resolveTargetClass(string $targetClassName): ResolvedClassInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface|null $resolvedClass */
        $resolvedClass = $this->fileResolver->resolve($targetClassName);

        if ($resolvedClass === null) {
            throw new SprykException(sprintf('Unable to resolve class for "%s"', $targetClassName));
        }

        return $resolvedClass;
    }

    /**
     * @return string
     */
    protected function getTargetClassName(): string
    {
        $target = $this->getTarget();
        [$className, $methodName] = explode('::', $target);

        return $className;
    }

    /**
     * @return string
     */
    protected function getTargetMethodName(): string
    {
        $target = $this->getTarget();
        [$className, $methodName] = explode('::', $target);

        return trim($methodName, '()');
    }

    /**
     * @return array
     */
    protected function getPluginClassNames(): array
    {
        return (array)$this->arguments->getArgument(static::ARGUMENT_PLUGIN)->getValue();
    }

    /**
     * @return string
     */
    protected function getOrganization(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_ORGANIZATION)->getValue();
    }

    /**
     * @return string
     */
    protected function getPluginBefore(): string
    {
        return $this->arguments->hasArgument(static::ARGUMENT_PLUGIN_BEFORE)
            ? (string)$this->arguments->getArgument(static::ARGUMENT_PLUGIN_BEFORE)->getValue()
            : '';
    }

    /**
     * @return string
     */
    protected function getPluginAfter(): string
    {
        return $this->arguments->hasArgument(static::ARGUMENT_PLUGIN_AFTER)
            ? (string)$this->arguments->getArgument(static::ARGUMENT_PLUGIN_AFTER)->getValue()
            : '';
    }

    /**
     * @return string|null
     */
    protected function getPluginIndex(): ?string
    {
        return $this->arguments->hasArgument(static::ARGUMENT_PLUGIN_INDEX)
            ? $this->arguments->getArgument(static::ARGUMENT_PLUGIN_INDEX)->getValue()
            : null;
    }
}
