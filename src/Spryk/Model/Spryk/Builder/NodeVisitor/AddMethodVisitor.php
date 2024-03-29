<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\NodeVisitorAbstract;

class AddMethodVisitor extends NodeVisitorAbstract
{
    public function __construct(protected ClassMethod $classMethodNode)
    {
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return mixed
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof Class_) && !($node instanceof Interface_)) {
            return null;
        }

        if ($node instanceof Interface_) {
            $this->classMethodNode->stmts = null;
        }

        $stmts = $node->stmts;
        $stmts[] = $this->classMethodNode;
        $node->stmts = $stmts;

        return $node;
    }
}
