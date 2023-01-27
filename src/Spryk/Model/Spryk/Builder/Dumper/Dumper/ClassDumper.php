<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\OrderStatementsInClassVisitor;

class ClassDumper implements ClassDumperInterface
{
    /**
     * @var \PhpParser\PrettyPrinter\Standard
     */
    protected Standard $classPrinter;

    /**
     * @var \PhpParser\Parser
     */
    protected Parser $parser;

    /**
     * @var \PhpParser\Lexer
     */
    protected Lexer $lexer;

    /**
     * @param \PhpParser\PrettyPrinter\Standard $classPrinter
     * @param \PhpParser\Parser $parser
     * @param \PhpParser\Lexer $lexer
     */
    public function __construct(
        Standard $classPrinter,
        Parser $parser,
        Lexer $lexer
    ) {
        $this->classPrinter = $classPrinter;
        $this->parser = $parser;
        $this->lexer = $lexer;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface> $resolvedFiles
     *
     * @return void
     */
    public function dump(array $resolvedFiles): void
    {
        foreach ($resolvedFiles as $resolved) {
            $classTokenTree = $this->orderStatementsInClass($resolved->getClassTokenTree());
            $fileContent = $this->classPrinter->printFormatPreserving(
                $classTokenTree,
                $resolved->getOriginalClassTokenTree(),
                $resolved->getTokens(),
            );
            $resolved->setContent($fileContent);
        }
    }

    /**
     * @param array $statements
     *
     * @return array
     */
    protected function orderStatementsInClass(array $statements): array
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new OrderStatementsInClassVisitor());

        return $traverser->traverse($statements);
    }
}
