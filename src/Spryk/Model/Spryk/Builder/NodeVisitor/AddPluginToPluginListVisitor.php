<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class AddPluginToPluginListVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected const ARRAY_MERGE_FUNCTION = 'array_merge';

    /**
     * @var string
     */
    protected const STATEMENT_ARRAY = 'Expr_Array';

    /**
     * @var string
     */
    protected const STATEMENT_CLASS_METHOD = 'Stmt_ClassMethod';

    /**
     * @var string
     */
    protected string $methodName;

    /**
     * @var string
     */
    protected string $pluginClassName;

    /**
     * @var string
     */
    protected string $before;

    /**
     * @var string
     */
    protected string $after;

    /**
     * @var string|null
     */
    protected ?string $index;

    /**
     * @var bool
     */
    protected bool $methodFound = false;

    /**
     * @param string $methodName
     * @param string $pluginClassName
     * @param string $before
     * @param string $after
     * @param string|null $index
     */
    public function __construct(string $methodName, string $pluginClassName, string $before = '', string $after = '', ?string $index = null)
    {
        $this->methodName = $methodName;
        $this->pluginClassName = ltrim($pluginClassName, '\\');
        $this->before = ltrim($before, '\\');
        $this->after = ltrim($after, '\\');
        $this->index = $index;
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node|int|null
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof ClassMethod && (string)$node->name === $this->methodName) {
            $this->methodFound = true;

            return null;
        }

        if ($this->methodFound) {
            if ($node instanceof FuncCall && $this->isArrayMergeFuncCallNode($node)) {
                $this->addNewPluginIntoArrayMergeFuncNode($node);

                return $this->successfullyProcessed();
            }

            if ($node instanceof Array_) {
                $this->addNewPlugin($node);

                return $this->successfullyProcessed();
            }
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return bool
     */
    protected function isArrayMergeFuncCallNode(FuncCall $node): bool
    {
        return $node->name instanceof Name && $node->name->parts[0] === static::ARRAY_MERGE_FUNCTION;
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return \PhpParser\Node
     */
    protected function addNewPluginIntoArrayMergeFuncNode(FuncCall $node): Node
    {
        if ($this->isPluginAddedInArrayMerge($node)) {
            return $node;
        }

        $node->args[] = new Arg($this->createArrayWithInstanceOf());

        return $node;
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return bool
     */
    protected function isPluginAddedInArrayMerge(FuncCall $node): bool
    {
        foreach ($node->getArgs() as $arg) {
            if (!$arg->value instanceof Array_) {
                continue;
            }

            if ($this->isPluginAdded($arg->value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \PhpParser\Node\Expr\Array_
     */
    protected function createArrayWithInstanceOf(): Array_
    {
        return new Array_(
            [$this->createArrayItemWithInstanceOf()],
        );
    }

    /**
     * @param \PhpParser\Node\Expr\Array_ $node
     *
     * @return \PhpParser\Node
     */
    protected function addNewPlugin(Array_ $node): Node
    {
        if ($this->isPluginAdded($node)) {
            return $node;
        }

        $items = [];
        $itemAdded = false;
        foreach ($node->items as $item) {
            if ($item === null) {
                continue;
            }
            if (!($item->value instanceof New_)) {
                continue;
            }
            if (!($item->value->class instanceof Name)) {
                continue;
            }
            $nodeClassName = $item->value->class->toString();
            if ($nodeClassName === $this->before) {
                $items[] = $this->createArrayItemWithInstanceOf();
                $items[] = $item;
                $itemAdded = true;

                continue;
            }
            if ($nodeClassName === $this->after) {
                $items[] = $item;
                $items[] = $this->createArrayItemWithInstanceOf();
                $itemAdded = true;

                continue;
            }

            $items[] = $item;
        }

        if (!$itemAdded) {
            $items[] = $this->createArrayItemWithInstanceOf();
        }

        $node->items = $items;

        return $node;
    }

    /**
     * @param \PhpParser\Node\Expr\Array_ $node
     *
     * @return bool
     */
    protected function isPluginAdded(Array_ $node): bool
    {
        foreach ($node->items as $item) {
            if ($item === null) {
                continue;
            }
            if (!($item->value instanceof New_)) {
                continue;
            }
            if (!($item->value->class instanceof Name)) {
                continue;
            }
            $nodeClassName = $item->value->class->toString();

            if ($nodeClassName === $this->pluginClassName && $this->isKeyEqualsToCurrentOne($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Expr\ArrayItem $node
     *
     * @return bool
     */
    protected function isKeyEqualsToCurrentOne(ArrayItem $node): bool
    {
        $nodeKey = $this->getArrayItemNodeKey($node);

        return ltrim((string)$nodeKey, '\\') === ltrim((string)$this->index, '\\');
    }

    /**
     * @param \PhpParser\Node\Expr\ArrayItem $node
     *
     * @return string|null
     */
    protected function getArrayItemNodeKey(ArrayItem $node): ?string
    {
        if ($node->key === null) {
            return null;
        }

        if ($node->key instanceof ClassConstFetch && $node->key->class instanceof Name && $node->key->name instanceof Identifier) {
            return sprintf('%s::%s', $node->key->class, $node->key->name);
        }

        if ($node->key instanceof String_) {
            return $node->key->value;
        }

        return null;
    }

    /**
     * @return \PhpParser\Node\Expr\ArrayItem
     */
    protected function createArrayItemWithInstanceOf(): ArrayItem
    {
        return new ArrayItem(
            (new BuilderFactory())->new(
                $this->getShortClassName($this->pluginClassName),
            ),
            $this->index ? $this->createIndexExpr($this->index) : null,
        );
    }

    /**
     * @param string $index
     *
     * @return \PhpParser\Node\Expr
     */
    protected function createIndexExpr(string $index): Expr
    {
        if (strpos($index, 'static::') === 0) {
            $indexParts = explode('::', $index);

            return new ClassConstFetch(
                new Name('static'),
                $indexParts[1],
            );
        }

        if (strpos($index, '::') !== false) {
            $indexParts = explode('::', $index);
            $classNamespaceChain = explode('\\', $indexParts[0]);

            return new ClassConstFetch(
                new Name(end($classNamespaceChain)),
                $indexParts[1],
            );
        }

        return new String_($index);
    }

    /**
     * @return int
     */
    protected function successfullyProcessed(): int
    {
        $this->methodFound = false;

        return NodeTraverser::DONT_TRAVERSE_CHILDREN;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getShortClassName(string $className): string
    {
        return ($pos = strrpos($className, '\\')) === false ? $className : substr($className, $pos + 1);
    }
}
