<?php

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
    public const ARGUMENT_TARGET_FQCN = 'targetFqcn';

    /**
     * @var string
     */
    public const ARGUMENT_TARGET_METHOD_NAME = 'targetMethodName';

    /**
     * @var string
     */
    public const ARGUMENT_SOURCE_FQCN = 'sourceFqcn';

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
     *
     * @throws SprykException
     */
    protected function build(): void
    {
        $targetClassName = $this->replaceOrganization($this->getTargetClassName());
        $targetMethodName = $this->getTargetMethodName();
        $sourceClassName = $this->getSourceClassName();

        $resolvedTargetClass = $this->resolveTargetClass($targetClassName);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new AddUseVisitor($sourceClassName));
        $traverser->addVisitor(
            new AddPluginToPluginListVisitor(
                $targetMethodName,
                $sourceClassName,
                $this->getPluginBefore(),
                $this->getPluginAfter(),
                $this->getPluginIndex()
            )
        );
        $newStmts = $traverser->traverse($resolvedTargetClass->getClassTokenTree());

        $resolvedTargetClass->setClassTokenTree($newStmts);
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
     * @return ResolvedClassInterface
     *
     * @throws SprykException
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
     * @param string $targetClassName
     *
     * @return string
     */
    protected function replaceOrganization(string $targetClassName): string
    {
        return (string)preg_replace('/(\w+)/', $this->getOrganization(), $targetClassName, 1);
    }

    /**
     * @return string
     */
    protected function getTargetClassName(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_TARGET_FQCN)->getValue();
    }

    /**
     * @return string
     */
    protected function getTargetMethodName(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_TARGET_METHOD_NAME)->getValue();
    }

    /**
     * @return string
     */
    protected function getSourceClassName(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_SOURCE_FQCN)->getValue();
    }

    /**
     * @return string
     */
    protected function getOrganization(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_ORGANIZATION)->getValue();
    }

    /**
     * @return string|null
     */
    protected function getPluginBefore(): ?string
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
