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
    public function __construct(protected Standard $classPrinter, protected Parser $parser, protected Lexer $lexer)
    {
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

    protected function orderStatementsInClass(array $statements): array
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new OrderStatementsInClassVisitor());

        return $traverser->traverse($statements);
    }
}
