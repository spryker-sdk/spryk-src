<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Module;

use Codeception\Module;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;

class AssertionModule extends Module
{
    /**
     * @return \SprykerSdkTest\Module\SprykHelper
     */
    protected function getSprykHelper(): SprykHelper
    {
        /** @var \SprykerSdkTest\Module\SprykHelper $sprykHelper */
        $sprykHelper = $this->getModule(SprykHelper::class);

        return $sprykHelper;
    }

    /**
     * @param string $classOrInterfaceName
     *
     * @return void
     */
    public function assertClassOrInterfaceExists(string $classOrInterfaceName): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->getSprykHelper()->getFileResolver()->resolve($classOrInterfaceName);
        $resolvedClasses = $this->getSprykHelper()->getFileResolver()->getResolvedByType(ResolvedClassInterface::class);

        $this->assertNotNull(
            $resolved,
            sprintf(
                "Expected that class or interface \"%s\" exists but was not found. Found classes: \n\n%s",
                $classOrInterfaceName,
                implode("\n", array_keys($resolvedClasses)),
            ),
        );
    }

    /**
     * @param string $classOrInterfaceName
     * @param string $extends
     *
     * @return void
     */
    public function assertClassOrInterfaceExtends(string $classOrInterfaceName, string $extends): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->getSprykHelper()->getFileResolver()->resolve($classOrInterfaceName);

        $this->assertNotNull(
            $resolved,
            sprintf(
                'Expected that class or interface "%s" exists but was not found.',
                $classOrInterfaceName,
            ),
        );

        $extendFullyQualified = $this->getNodeFinder()->findExtends($resolved->getClassTokenTree(), $extends);

        $this->assertNotNull(
            $extendFullyQualified,
            sprintf(
                'Expected that class or interface "%s" extends "%s" but was not found.',
                $classOrInterfaceName,
                $extends,
            ),
        );
    }

    /**
     * @param string $classOrInterfaceName
     *
     * @return void
     */
    public function assertClassOrInterfaceDoesNotExist(string $classOrInterfaceName): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->getSprykHelper()->getFileResolver()->resolve($classOrInterfaceName);

        $this->assertNull(
            $resolved,
            sprintf(
                'Expected that class or interface "%s" does not exist but it was found.',
                $classOrInterfaceName,
            ),
        );
    }

    /**
     * @param string $className
     *
     * @return void
     */
    public function assertClassExists(string $className): void
    {
        $this->assertNotNull(
            $this->getSprykHelper()->getFileResolver()->resolve($className),
            sprintf(
                'Expected that class "%s" exists but class was not found.',
                $className,
            ),
        );
    }

    /**
     * @param string $className
     *
     * @return void
     */
    public function assertClassDoesNotExist(string $className): void
    {
        $this->assertNull(
            $this->getSprykHelper()->getFileResolver()->resolve($className),
            sprintf(
                'Expected that class "%s" does not exist but class was found.',
                $className,
            ),
        );
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public function assertClassOrInterfaceHasMethod(string $className, string $methodName): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $nodeFinder = $this->getNodeFinder();

        $method = $nodeFinder->findMethodNode($resolved->getClassTokenTree(), $methodName);

        $this->assertInstanceOf(
            ClassMethod::class,
            $method,
            sprintf(
                'Expected that class "%s" has method "%s" but method not found. Found methods: %s',
                $className,
                $methodName,
                implode(', ', $this->getMethodNamesFromResolvedClass($resolved)),
            ),
        );
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public function assertClassOrInterfaceDoesNotHasMethod(string $className, string $methodName): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $nodeFinder = $this->getNodeFinder();

        $method = $nodeFinder->findMethodNode($resolved->getClassTokenTree(), $methodName);

        $this->assertNull(
            $method,
            sprintf(
                'Expected that class "%s" does not has method "%s" but method was found.',
                $className,
                $methodName,
            ),
        );
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolvedClass
     *
     * @return array<string, string>
     */
    protected function getMethodNamesFromResolvedClass(ResolvedClassInterface $resolvedClass): array
    {
        return $this->getNodeFinder()->findMethodNames($resolvedClass->getClassTokenTree());
    }

    /**
     * @param string $className
     * @param string $constantName
     * @param string $constantValue
     * @param string $visibility
     *
     * @return void
     */
    public function assertClassHasConstant(string $className, string $constantName, string $constantValue, string $visibility): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $classConst = $this->getNodeFinder()->findConstantNode($resolved->getClassTokenTree(), $constantName);

        $this->assertInstanceOf(
            ClassConst::class,
            $classConst,
            sprintf(
                'Expected that class "%s" has constant "%s" but constant not found.',
                $className,
                $constantName,
            ),
        );

        $constantVisibility = $this->getVisibilityFromClassConst($classConst);

        $this->assertSame(
            $visibility,
            $constantVisibility,
            sprintf(
                'Expected that class constant "%s" visibility is "%s" but it is "%s".',
                $constantName,
                $visibility,
                $constantVisibility,
            ),
        );

        $classConstValue = $classConst->consts[0]->value->value;

        $this->assertSame(
            $constantValue,
            $classConstValue,
            sprintf(
                'Expected that class constant "%s" value is "%s" but it is "%s".',
                $constantName,
                $constantValue,
                $classConstValue,
            ),
        );
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassConst $classConst
     *
     * @return string|null
     */
    protected function getVisibilityFromClassConst(ClassConst $classConst): ?string
    {
        if ($classConst->isPublic()) {
            return 'public';
        }
        if ($classConst->isProtected()) {
            return 'protected';
        }
        if ($classConst->isPrivate()) {
            return 'private';
        }

        return null;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $expectBody
     *
     * @return void
     */
    public function assertMethodBody(string $className, string $methodName, string $expectBody): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $classMethod = $this->getNodeFinder()->findMethodNode($resolved->getClassTokenTree(), $methodName);

        $printer = new Standard();
        $methodBodyCode = $printer->prettyPrint($classMethod->stmts);

        $this->assertSame($expectBody, $methodBodyCode);
    }

    /**
     * @param string $className
     * @param string $resourceRouteMethod
     *
     * @return void
     */
    public function assertRouteAdded(string $className, string $resourceRouteMethod): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $methodCallNode = $this->getNodeFinder()->findMethodCallNode($resolved->getClassTokenTree(), $resourceRouteMethod);

        $this->assertInstanceOf(MethodCall::class, $methodCallNode, sprintf('Expected to find a method call "%s::%s" but was not found.', $className, $resourceRouteMethod));
    }

    /**
     * @param string $expectedDocBlock
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public function assertDocBlockForClassMethod(string $expectedDocBlock, string $className, string $methodName): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $nodeFinder = $this->getNodeFinder();

        $method = $nodeFinder->findMethodNode($resolved->getClassTokenTree(), $methodName);

        if (!$method) {
            $this->assertTrue(false, sprintf('Could not find a method by name "%s"', $methodName));
        }

        $docBlock = $method->getDocComment()->getText();
        $this->assertSame($expectedDocBlock, $docBlock);
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface
     */
    protected function getNodeFinder(): NodeFinderInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder */
        $nodeFinder = $this->getSprykHelper()->getClass(NodeFinderInterface::class);

        return $nodeFinder;
    }

    /**
     * @param string $className
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface
     */
    protected function getResolvedByClassName(string $className): ResolvedClassInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $this->getSprykHelper()->getFileResolver()->resolve($className);

        $this->assertInstanceOf(ResolvedClassInterface::class, $resolved, sprintf('Expected class "%s" not found.', $className));

        return $resolved;
    }

    /**
     * @param int $count
     * @param string $pathToSchemaFile
     * @param string $tableName
     *
     * @return void
     */
    public function assertTableCount(int $count, string $pathToSchemaFile, string $tableName): void
    {
        $this->assertFileExists($pathToSchemaFile, 'Expected schema file does not exists.');

        $simpleXmlElement = simplexml_load_file($pathToSchemaFile);

        if ($simpleXmlElement === false) {
            $this->assertTrue($simpleXmlElement, 'Unable to load schema from file.');

            return;
        }

        $tableXmlElements = $simpleXmlElement->xpath('//table[@name="' . $tableName . '"]');

        if ($tableXmlElements === false) {
            $this->assertTrue($tableXmlElements, 'Expected table not found in schema.');

            return;
        }

        $this->assertCount($count, $tableXmlElements, 'Expected table not found in schema.');
    }

    /**
     * @param string $className
     * @param string $implement
     *
     * @return void
     */
    public function assertClassImplements(string $className, string $implement): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $implementNode = $this->getNodeFinder()->findImplements($resolved->getClassTokenTree(), $implement);

        $this->assertInstanceOf(
            Name::class,
            $implementNode,
            sprintf(
                'Expected that class "%s" implements "%s" but node not found.',
                $className,
                $implement,
            ),
        );
    }

    /**
     * @param string $className
     * @param string $extended
     *
     * @return void
     */
    public function assertClassExtends(string $className, string $extended): void
    {
        $resolved = $this->getResolvedByClassName($className);
        $extendsNode = $this->getNodeFinder()->findExtends($resolved->getClassTokenTree(), $extended);

        $this->assertInstanceOf(
            Name::class,
            $extendsNode,
            sprintf(
                'Expected that class "%s" extends "%s" but node not found.',
                $className,
                $extended,
            ),
        );
    }

    /**
     * Assert that a method inside of a class has method call.
     *
     * @example
     * ```
     * class FooBar { // className
     *     public function zipZap() { // classMethodName
     *         $this->bazBat(); // methodCallName
     *     }
     * }
     * ```
     *
     * @param string $className
     * @param string $classMethodName
     * @param string $methodCallName
     *
     * @return void
     */
    public function assertClassMethodHasMethodCall(string $className, string $classMethodName, string $methodCallName): void
    {
        $this->assertClassOrInterfaceHasMethod($className, $classMethodName);

        $resolved = $this->getResolvedByClassName($className);
        $nodeFinder = $this->getNodeFinder();

        $method = $nodeFinder->findMethodNode($resolved->getClassTokenTree(), $classMethodName);

        if (!$method) {
            $this->assertTrue(false, sprintf('Class method "%s" not found.', $classMethodName));
        }

        $methodCallNode = $this->getNodeFinder()->findMethodCallNode($method->getStmts(), $methodCallName);

        $this->assertInstanceOf(
            MethodCall::class,
            $methodCallNode,
            sprintf(
                'Expected that class "%s::%s()" calls method "%s" but method call not found.',
                $className,
                $classMethodName,
                $methodCallName,
            ),
        );
    }
}
