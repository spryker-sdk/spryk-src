<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder as PhpParserNodeFinder;

class NodeFinder implements NodeFinderInterface
{
    /**
     * @param array<\PhpParser\Node\Stmt> $tokens
     * @param string $extends
     *
     * @return \PhpParser\Node\Name|null
     */
    public function findExtends(array $tokens, string $extends): ?Name
    {
        /** @var \PhpParser\Node\Stmt\Class_|null $class */
        $class = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) {
            return $node instanceof Class_;
        });

        if (!$class) {
            return null;
        }

        $name = $class->extends;

        if (!$name || (string)$name !== $extends) {
            return null;
        }

        return $name;
    }

    /**
     * @param array $tokens
     * @param string $methodName
     *
     * @return \PhpParser\Node\Stmt\ClassMethod|null
     */
    public function findMethodNode(array $tokens, string $methodName): ?ClassMethod
    {
        /** @var \PhpParser\Node\Stmt\ClassMethod|null $node */
        $node = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) use ($methodName) {
            return $node instanceof ClassMethod
                && $node->name->toString() === $methodName;
        });

        return $node;
    }

    /**
     * @param array $tokens
     * @param string $methodName
     *
     * @return \PhpParser\Node\Expr\MethodCall|null
     */
    public function findMethodCallNode(array $tokens, string $methodName): ?MethodCall
    {
        /** @var \PhpParser\Node\Expr\MethodCall|null $node */
        $node = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) use ($methodName) {
            if (!($node instanceof MethodCall) || !($node->name instanceof Identifier)) {
                return false;
            }

            /** @var \PhpParser\Node\Identifier $name */
            $name = $node->name;

            return (string)$name === $methodName;
        });

        return $node;
    }

    /**
     * @param array $tokens
     *
     * @return array
     */
    public function findMethods(array $tokens): array
    {
        /** @var array<\PhpParser\Node\Stmt\ClassMethod> $methodNodes */
        $methodNodes = (new PhpParserNodeFinder())->find($tokens, function (Node $node) {
            return $node instanceof ClassMethod;
        });

        $methods = [];

        foreach ($methodNodes as $methodNode) {
            $methods[(string)$methodNode->name] = $methodNode;
        }

        return $methods;
    }

    /**
     * @param array $tokens
     *
     * @return array
     */
    public function findMethodNames(array $tokens): array
    {
        $methodNodes = $this->findMethods($tokens);

        $methodNames = [];

        foreach ($methodNodes as $methodNode) {
            $methodNames[(string)$methodNode->name] = (string)$methodNode->name;
        }

        return $methodNames;
    }

    /**
     * @param array $tokens
     * @param string $constantName
     *
     * @return \PhpParser\Node\Stmt\ClassConst|null
     */
    public function findConstantNode(array $tokens, string $constantName): ?ClassConst
    {
        /** @var \PhpParser\Node\Stmt\ClassConst|null $node */
        $node = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) use ($constantName) {
            if (!($node instanceof ClassConst)) {
                return false;
            }

            foreach ($node->consts as $const) {
                if ($const->name->name === $constantName) {
                    return true;
                }
            }

            return false;
        });

        return $node;
    }

    /**
     * @param array $tokens
     *
     * @return string|null
     */
    public function findClassOrInterfaceName(array $tokens): ?string
    {
        /** @var \PhpParser\Node\Stmt\Namespace_|null $namespaceNode */
        $namespaceNode = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) {
            if (!($node instanceof Namespace_)) {
                return false;
            }

            return true;
        });

        if (!$namespaceNode) {
            return null;
        }

        /** @var \PhpParser\Node\Stmt\Class_|\PhpParser\Node\Stmt\Interface_|null $classOrInterfaceNode */
        $classOrInterfaceNode = (new PhpParserNodeFinder())->findFirst($tokens, function (Node $node) {
            if (!($node instanceof Class_) && !($node instanceof Interface_)) {
                return false;
            }

            return true;
        });

        if ($classOrInterfaceNode) {
            return sprintf('%s\\%s', (string)$namespaceNode->name, (string)$classOrInterfaceNode->name);
        }

        return null;
    }
}
