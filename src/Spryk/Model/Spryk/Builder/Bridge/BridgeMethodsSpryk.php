<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Bridge;

use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddMethodVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;
use SprykerSdk\Spryk\SprykConfig;

class BridgeMethodsSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const ARGUMENT_SOURCE_CLASS = 'sourceClass';

    /**
     * @var string
     */
    public const ARGUMENT_METHODS = 'methods';

    /**
     * @var string
     */
    public const ARGUMENT_DEPENDENT_MODULE = 'dependentModule';

    /**
     * @var string
     */
    public const ARGUMENT_DEPENDENCY_TYPE = 'dependencyType';

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder
     */
    public function __construct(
        SprykConfig $config,
        FileResolverInterface $fileResolver,
        protected NodeFinderInterface $nodeFinder,
    ) {
        parent::__construct($config, $fileResolver);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'bridgeMethods';
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        $target = $this->getTarget();
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->fileResolver->resolve($target);

        $methodsToAdd = $this->getBridgeMethodsToAdd($resolved);

        $builderFactory = new BuilderFactory();

        foreach ($methodsToAdd as $classMethodNode) {
            $newClassMethodNode = $this->getNewClassMethodNode($builderFactory, $classMethodNode);

            $traverser = new NodeTraverser();
            $traverser->addVisitor(new AddMethodVisitor($newClassMethodNode));
            $newStmts = $traverser->traverse($resolved->getClassTokenTree());

            $resolved->setClassTokenTree($newStmts);

            $this->log(sprintf(
                'Added method "<fg=green>%s</>" to "<fg=green>%s</>"',
                $classMethodNode->name,
                $this->getTarget(),
            ));
        }
    }

    /**
     * Copy from an existing method and create a "fresh" ClassMethod without attributes. Using the original ClassMethod
     * with its attributes will crash the PrettyPrinter.
     */
    protected function getNewClassMethodNode(BuilderFactory $builderFactory, ClassMethod $classMethodNode): ClassMethod
    {
        $newClassMethod = $builderFactory->method($classMethodNode->name);
        $newClassMethod->makePublic();

        foreach ($classMethodNode->getParams() as $param) {
            $param->setAttributes([]);
            if ($param->type) {
                $param->type->setAttributes([]);
            }
            $newClassMethod->addParam($param);
        }

        if ($classMethodNode->getDocComment()) {
            $newClassMethod->setDocComment(new Doc($classMethodNode->getDocComment()->getText()));
        }

        $returnType = $classMethodNode->getReturnType();

        if ($returnType) {
            $returnType->setAttributes([]);
            $newClassMethod->setReturnType($returnType);
        }

        $variableName = lcfirst($this->getStringArgument(static::ARGUMENT_DEPENDENT_MODULE)) . $this->getStringArgument(static::ARGUMENT_DEPENDENCY_TYPE);

        $args = [];

        foreach ($classMethodNode->params as $param) {
            $var = $param->var;
            $var->setAttributes([]);
            $args[] = $var;
        }

        $methodCall = $builderFactory->methodCall(
            $builderFactory->propertyFetch(
                $builderFactory->var('this'),
                $variableName,
            ),
            $classMethodNode->name,
            $builderFactory->args($args),
        );
        $returnStatement = new Return_($methodCall);

        $newClassMethod->addStmts([$returnStatement]);

        return $newClassMethod->getNode();
    }

    /**
     * @return array<\PhpParser\Node\Stmt\ClassMethod>
     */
    protected function getBridgeMethodsToAdd(ResolvedClassInterface $resolvedClass): array
    {
        $sourceClassName = $this->getSourceClassName();
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedSourceClass */
        $resolvedSourceClass = $this->fileResolver->resolve($sourceClassName);

        $sourceClassMethods = $this->nodeFinder->findMethods($resolvedSourceClass->getClassTokenTree());
        $targetClassMethods = $this->getMethodNamesFromResolvedClass($resolvedClass);

        $methods = [];

        $methodNames = $this->getMethodNames();

        foreach ($methodNames as $methodName) {
            if (!isset($targetClassMethods[$methodName])) {
                $methods[] = $sourceClassMethods[$methodName];
            }
        }

        return $methods;
    }

    /**
     * @return array<string, string>
     */
    protected function getMethodNamesFromResolvedClass(ResolvedClassInterface $resolvedClass): array
    {
        return $this->nodeFinder->findMethodNames($resolvedClass->getClassTokenTree());
    }

    /**
     * @phpstan-return class-string
     */
    protected function getSourceClassName(): string
    {
        /** @phpstan-var class-string */
        return $this->getStringArgument(static::ARGUMENT_SOURCE_CLASS);
    }

    protected function getMethodNames(): array
    {
        return $this->getArrayArgument(static::ARGUMENT_METHODS);
    }
}
