<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class ReplaceMethodBodyNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(protected string $methodName, protected ClassMethod $classMethod)
    {
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return mixed
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof ClassMethod && (string)$node->name === $this->methodName) {
            $node->stmts = $this->classMethod->stmts;

            return $node;
        }
    }
}
