<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Glue\ApiApplication\Resource;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use SprykerSdk\Spryk\Exception\NotAFullyQualifiedClassNameException;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddGlueResourceMethodVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;
use SprykerSdk\Spryk\SprykConfig;

class AddApiApplicationResourceMethodSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const ARGUMENT_FULLY_QUALIFIED_CLASS_NAME_PATTERN = 'fqcnPattern';

    /**
     * @var string
     */
    public const ARGUMENT_RESOURCE = 'resource';

    /**
     * @var string
     */
    public const ARGUMENT_RESOURCE_DATA_OBJECT = 'resourceDataObject';

    /**
     * @var string
     */
    public const ARGUMENT_METHOD = 'httpMethod';

    /**
     * @var string
     */
    public const ARGUMENT_IS_BULK = 'isBulk';

    /**
     * @var string
     */
    protected const COLLECTION_SUFFIX = 'Collection';

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder
     * @param \PhpParser\Parser $parser
     * @param \PhpParser\Lexer $lexer
     */
    public function __construct(
        SprykConfig $config,
        FileResolverInterface $fileResolver,
        protected NodeFinderInterface $nodeFinder,
        protected Parser $parser,
        protected Lexer $lexer,
    ) {
        parent::__construct($config, $fileResolver);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'addApiApplicationRestResourceMethod';
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        $resolvedClass = $this->getTargetClass();

        if ($resolvedClass === null) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedClass */
        $resolvedClass = $this->getTargetClass();

        $methodName = $this->getMethodName();
        $resourceDataObject = $this->getResourceDataObject();

        $methodName = sprintf('set%s', ucfirst(strtolower($methodName)) . ($this->getIsBulk() ? static::COLLECTION_SUFFIX : ''));

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new AddGlueResourceMethodVisitor($methodName, $resourceDataObject, $this->nodeFinder));
        $newStmts = $traverser->traverse($resolvedClass->getClassTokenTree());

        $resolvedClass->setClassTokenTree($newStmts);

        $this->log(sprintf(
            'Added method "<fg=green>%s</>" to "<fg=green>%s</>"',
            $this->getMethodName(),
            $this->getTarget(),
        ));

        $this->log(sprintf('Updated <fg=green>%s</>', $resolvedClass->getClassName()));
    }

    protected function getTargetClass(): ?ResolvedClassInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface|null $resolvedClass */
        $resolvedClass = $this->fileResolver->resolve($this->getTargetClassName());

        return $resolvedClass;
    }

    protected function getTargetClassName(): string
    {
        $className = $this->getTarget();

        if (!str_contains($className, '\\') && $this->arguments->hasArgument(static::ARGUMENT_FULLY_QUALIFIED_CLASS_NAME_PATTERN)) {
            $className = $this->getStringArgument(static::ARGUMENT_FULLY_QUALIFIED_CLASS_NAME_PATTERN);
        }

        $className = str_replace(DIRECTORY_SEPARATOR, '\\', $className);

        $this->assertFullyQualifiedClassName($className);

        return $className;
    }

    /**
     * @throws \SprykerSdk\Spryk\Exception\NotAFullyQualifiedClassNameException
     */
    protected function assertFullyQualifiedClassName(string $className): void
    {
        if (!str_contains($className, '\\')) {
            throw new NotAFullyQualifiedClassNameException(sprintf(
                'Expected a fully qualified class name for reflection but got "%s". ' .
                'Make sure you pass a fully qualified class name in the "%s" argument or use the "%s" argument with a value like "%s" in your spryk ' .
                'to be able to compute the fully qualified class name from the given arguments.',
                $className,
                static::ARGUMENT_TARGET,
                static::ARGUMENT_FULLY_QUALIFIED_CLASS_NAME_PATTERN,
                '{{ organization }}\\Zed\\{{ module }}\\Business\\{{ subDirectory | convertToClassNameFragment }}\\{{ className }}',
            ));
        }
    }

    protected function getMethodName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_METHOD);
    }

    protected function getResourceDataObject(): string
    {
        return $this->getStringArgument(static::ARGUMENT_RESOURCE_DATA_OBJECT);
    }

    protected function getIsBulk(): bool
    {
        return (bool)$this->arguments->getArgument(static::ARGUMENT_IS_BULK)->getValue();
    }
}
