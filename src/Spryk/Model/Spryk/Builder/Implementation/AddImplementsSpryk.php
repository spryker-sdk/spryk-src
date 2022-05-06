<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Implementation;

use PhpParser\NodeTraverser;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddImplementsVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddUseVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Filter\ClassNameShortFilter;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;
use SprykerSdk\Spryk\SprykConfig;

class AddImplementsSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const SPRYK_NAME = 'addImplements';

    /**
     * @var string
     */
    public const ARGUMENT_INTERFACE = 'interface';

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface
     */
    protected NodeFinderInterface $nodeFinder;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Filter\ClassNameShortFilter
     */
    protected ClassNameShortFilter $classNameShortFilter;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder
     * @param \SprykerSdk\Spryk\Model\Spryk\Filter\ClassNameShortFilter $classNameShortFilter
     */
    public function __construct(
        SprykConfig $config,
        FileResolverInterface $fileResolver,
        NodeFinderInterface $nodeFinder,
        ClassNameShortFilter $classNameShortFilter
    ) {
        parent::__construct($config, $fileResolver);
        $this->nodeFinder = $nodeFinder;
        $this->classNameShortFilter = $classNameShortFilter;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYK_NAME;
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface|null $resolvedClass */
        $resolvedClass = $this->fileResolver->resolve($this->getTarget());

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface|null $resolvedInterface */
        $resolvedInterface = $this->fileResolver->resolve($this->getInterface());

        return (
            $resolvedClass instanceof ResolvedClassInterface
            && $resolvedInterface instanceof ResolvedClassInterface
            && !$this->isAlreadyImplements($resolvedClass, $resolvedInterface)
        );
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedClass */
        $resolvedClass = $this->fileResolver->resolve($this->getTarget());

        $traverser = new NodeTraverser();

        $traverser->addVisitor(new AddUseVisitor($this->getInterface()));
        $traverser->addVisitor(new AddImplementsVisitor($this->classNameShortFilter->filter($this->getInterface())));

        $newStmts = $traverser->traverse($resolvedClass->getClassTokenTree());
        $resolvedClass->setClassTokenTree($newStmts);

        $this->log(sprintf(
            'Added implements "<fg=green>%s</>" to "<fg=green>%s</>"',
            $this->getInterface(),
            $this->getTarget(),
        ));
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedClass
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedInterface
     *
     * @return bool
     */
    protected function isAlreadyImplements(
        ResolvedClassInterface $resolvedClass,
        ResolvedClassInterface $resolvedInterface
    ): bool {
        return $this->nodeFinder->findImplements($resolvedClass->getClassTokenTree(), $resolvedInterface->getClassName()) !== null;
    }

    /**
     * @return string
     */
    protected function getInterface(): string
    {
        return $this->getStringArgument(static::ARGUMENT_INTERFACE);
    }
}
