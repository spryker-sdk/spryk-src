<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeVisitorAbstract;

class AddConstantVisitor extends NodeVisitorAbstract
{
    public function __construct(protected string $constantName, protected mixed $constantValue, protected string $modifier)
    {
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return mixed
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof Class_)) {
            return $node;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassConst && $stmt->consts) {
                foreach ($stmt->consts as $const) {
                    if ($const->name->name === $this->constantName) {
                        return $node;
                    }
                }
            }
        }

        $node->stmts[] = $this->createConst();

        return $node;
    }

    protected function createConst(): ClassConst
    {
        $modifier = match ($this->modifier) {
            'protected' => Class_::MODIFIER_PROTECTED,
            'private' => Class_::MODIFIER_PRIVATE,
            default => Class_::MODIFIER_PUBLIC,
        };

        return (new ClassConst([new Const_($this->constantName, $this->prepareValue())], $modifier));
    }

    protected function prepareValue(): Expr
    {
        return (new BuilderFactory())->val($this->constantValue);
    }
}
