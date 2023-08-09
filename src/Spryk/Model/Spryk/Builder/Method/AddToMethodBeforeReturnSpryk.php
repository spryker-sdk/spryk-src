<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Method;

use PhpParser\Lexer;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\AddExpressionToMethodBeforeReturnVisitor;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;
use SprykerSdk\Spryk\SprykConfig;

class AddToMethodBeforeReturnSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    protected const ARGUMENT_TEMPLATE = 'template';

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface
     */
    protected TemplateRendererInterface $renderer;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface
     */
    protected NodeFinderInterface $nodeFinder;

    /**
     * @var \PhpParser\Parser
     */
    protected Parser $parser;

    /**
     * @var \PhpParser\Lexer
     */
    protected Lexer $lexer;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface $renderer
     * @param \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder
     * @param \PhpParser\Parser $parser
     * @param \PhpParser\Lexer $lexer
     */
    public function __construct(
        SprykConfig $config,
        FileResolverInterface $fileResolver,
        TemplateRendererInterface $renderer,
        NodeFinderInterface $nodeFinder,
        Parser $parser,
        Lexer $lexer,
    ) {
        parent::__construct($config, $fileResolver);

        $this->renderer = $renderer;
        $this->nodeFinder = $nodeFinder;
        $this->parser = $parser;
        $this->lexer = $lexer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'addToMethodBeforeReturn';
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        return !$this->isDeclared();
    }

    /**
     * Find out if the content is already added to the method method.
     *
     * @return bool
     */
    protected function isDeclared(): bool
    {
        $content = $this->getContent();

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface|null $resolved */
        $resolved = $this->fileResolver->resolve($this->getTarget());

        if (!$resolved) {
            return true;
        }

        if (str_contains($resolved->getContent(), $content)) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        $methodName = $this->getMethodName();

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->fileResolver->resolve($this->getTarget());

        $expression = $this->getExpression();

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new AddExpressionToMethodBeforeReturnVisitor($expression, $methodName));
        $newStmts = $traverser->traverse($resolved->getClassTokenTree());

        $resolved->setClassTokenTree($newStmts);

        $this->log(
            sprintf(
                'Added the content to "%s::%s()".',
                $this->getTarget(),
                $this->getMethodName(),
            ),
        );
    }

    /**
     * @return \PhpParser\Node\Stmt\Expression
     */
    protected function getExpression(): Expression
    {
        $expressionContent = $this->getContentForExpression();

        $expressionContent = sprintf('<?php %s', $expressionContent);

        /** @var array<\PhpParser\Node\Stmt> $expressions */
        $expressions = $this->parser->parse($expressionContent);

        /** @var \PhpParser\Node\Stmt\Expression $expression */
        $expression = $expressions[0];

        return $expression;
    }

    /**
     * @return string
     */
    protected function getContentForExpression(): string
    {
        if ($this->arguments->hasArgument('body')) {
            return $this->arguments->getArgument('body');
        }

        $templateName = $this->getTemplateName();

        return $this->renderer->render(
            $templateName,
            $this->arguments->getArguments(),
        );
    }

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_TEMPLATE);
    }

    /**
     * @return string
     */
    protected function getMethodName(): string
    {
        return $this->arguments->getArgument('methodName')->getValue();
    }

    /**
     * @return string
     */
    protected function getContent(): string
    {
        return $this->arguments->getArgument('body')->getValue();
    }
}
