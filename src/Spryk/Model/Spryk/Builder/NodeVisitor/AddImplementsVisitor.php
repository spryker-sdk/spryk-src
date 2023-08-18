<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;

class AddImplementsVisitor extends NodeVisitorAbstract
{
    public function __construct(protected string $interface)
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

        $node->implements[] = new Name($this->interface);

        return $node;
    }
}
