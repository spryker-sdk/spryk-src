<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Namespace_;

class AddUseVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected const STATEMENT_NAMESPACE = 'Stmt_Namespace';

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string|null
     */
    protected $alias;

    /**
     * @param string $className
     * @param string|null $alias
     */
    public function __construct(string $className, ?string $alias = null)
    {
        $this->className = trim($className, '\\');
        $this->alias = $alias;
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node|array<\PhpParser\Node>|int|null
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof Namespace_)) {
            return $node;
        }

        if ($this->useAdded($node->stmts)) {
            return $node;
        }

        $uses = [];
        $other = [];
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Use_) {
                $uses[] = $stmt;

                continue;
            }

            $other[] = $stmt;
        }

        $uses[] = $this->createUse();

        uasort($uses, function (Use_ $nodeA, Use_ $nodeB) {
            return $this->compareUseStatements($nodeA, $nodeB);
        });

        $node->stmts = array_merge($uses, $other);

        return $node;
    }

    /**
     * @param array<\PhpParser\Node\Stmt> $stmts
     *
     * @return bool
     */
    protected function useAdded(array $stmts): bool
    {
        foreach ($stmts as $stmt) {
            if (!($stmt instanceof Use_)) {
                continue;
            }
            foreach ($stmt->uses as $use) {
                if ($use->name->toString() === $this->className) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Stmt\Use_ $nodeA
     * @param \PhpParser\Node\Stmt\Use_ $nodeB
     *
     * @return int
     */
    protected function compareUseStatements(Use_ $nodeA, Use_ $nodeB): int
    {
        $aNameParts = explode('\\', $nodeA->uses[0]->name->toString());
        $bNameParts = explode('\\', $nodeB->uses[0]->name->toString());

        $minPartsCount = min(count($aNameParts), count($bNameParts));
        for ($i = 0; $i < $minPartsCount; $i++) {
            $comparison = strcmp($aNameParts[$i], $bNameParts[$i]);
            if ($comparison === 0) {
                continue;
            }

            return $comparison;
        }

        return count($aNameParts) <=> count($bNameParts);
    }

    /**
     * @return \PhpParser\Node\Stmt\Use_
     */
    protected function createUse(): Use_
    {
        $use = (new BuilderFactory())->use($this->className);

        if ($this->alias) {
            $use = $use->as($this->alias);
        }

        /** @var \PhpParser\Node\Stmt\Use_ $node */
        $node = $use->getNode();

        return $node;
    }
}
