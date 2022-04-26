<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Arg;

class AddGlueResourceMethodVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected string $methodName;

    /**
     * @var string
     */
    protected string $resourceDataObjectName;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface
     */
    protected NodeFinderInterface $nodeFinder;

    /**
     * @var string[]
     */
    protected $methodNamesRequireDomainEntityTransfer = [
        'setPost',
        'setPatch',
    ];

    /**
     * @param string $methodName
     * @param string $resourceDataObjectName
     * @param \SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface $nodeFinder
     */
    public function __construct(string $methodName, string $resourceDataObjectName, NodeFinderInterface $nodeFinder)
    {
        $this->methodName = $methodName;
        $this->resourceDataObjectName = $resourceDataObjectName;
        $this->nodeFinder = $nodeFinder;
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node|array|int|null
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof ClassMethod) || (string)$node->name !== 'getDeclaredMethods') {
            return $node;
        }

        // Statements differ from the way the method body is defined.
        //
        // Case 1 - Direct return, inline assignment (prefered style)
        //
        // return (new GlueResourceMethodCollectionTransfer())
        //     ->setPost((new GlueResourceMethodConfigurationTransfer())->setAttributes(...));
        //
        //
        // Case 2 - Direct return, outline assignment
        //
        // $postGlueResourceMethodConfigurationTransfer = new GlueResourceMethodConfigurationTransfer();
        // $postGlueResourceMethodConfigurationTransfer->setAttributes(...);
        //
        // return (new GlueResourceMethodCollectionTransfer())
        //     ->setPost($postGlueResourceMethodConfigurationTransfer);
        //
        //
        // Case 3 - Late return, inline assignment
        //
        // $glueResourceMethodCollectionTransfer = new GlueResourceMethodCollectionTransfer();
        //
        // return $glueResourceMethodCollectionTransfer
        //     ->setPost((new GlueResourceMethodConfigurationTransfer())->setAttributes(TestTransfer::class));
        //
        //
        // Case 4 - Late return, outline assignment
        //
        // $glueResourceMethodCollectionTransfer = new GlueResourceMethodCollectionTransfer();
        // $postGlueResourceMethodConfigurationTransfer = new GlueResourceMethodConfigurationTransfer();
        // $postGlueResourceMethodConfigurationTransfer->setAttributes(...);
        //
        // return $glueResourceMethodCollectionTransfer
        //     ->setPost($postGlueResourceMethodConfigurationTransfer);

        $stmts = $node->stmts;

        // Return when the method already exists
        if ($this->nodeFinder->findMethodCallNode($stmts, $this->methodName)) {
            return $node;
        }

        // Assumption for Case 1
        $stmts[0]->expr = (new BuilderFactory())->methodCall(
            $stmts[0]->expr,
            $this->methodName,
            $this->getArgumentsForMethodCall()
        );

        $node->stmts = $stmts;

        return $node;
    }

    /**
     * @return array<Arg>
     */
    protected function getArgumentsForMethodCall(): array
    {
        $builderFactory = new BuilderFactory();

        if (in_array($this->methodName, $this->methodNamesRequireDomainEntityTransfer)) {
            // Add method call with (new GlueResourceMethodConfigurationTransfer())->setAttributes(DomainEntityTransfer::class)
            return $builderFactory->args([
                $builderFactory->methodCall(
                    $builderFactory->new('\Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer'),
                    'setAttributes',
                    $builderFactory->args([
                        $builderFactory->classConstFetch($this->resourceDataObjectName, 'class')
                    ])
                )
            ]);
        }

        return $builderFactory->args([
            $builderFactory->new('\Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer'),
        ]);
    }
}
