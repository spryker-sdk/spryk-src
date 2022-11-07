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
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeVisitorAbstract;
use SprykerSdk\Spryk\Model\Spryk\NodeFinder\NodeFinderInterface;

class AddGlueResourceMethodVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected const CONFIGURATION_TRANSFER_FQCN = '\Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer';

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
     * @var array<string>
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
     * @return mixed
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof ClassMethod) || (string)$node->name !== 'getDeclaredMethods') {
            return null;
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

        if (!$stmts) {
            return null;
        }

        // Return when the method already exists
        if ($this->nodeFinder->findMethodCallNode($stmts, $this->methodName)) {
            return $node;
        }

        $return = $stmts[0];

        if ($return instanceof Return_ && $return->expr !== null) {
            // Assumption for Case 1
            $return->expr = (new BuilderFactory())->methodCall(
                $return->expr,
                $this->methodName,
                $this->getArgumentsForMethodCall(),
            );

            $node->stmts = $stmts;
        }

        return $node;
    }

    /**
     * @return array<\PhpParser\Node\Arg>
     */
    protected function getArgumentsForMethodCall(): array
    {
        $builderFactory = new BuilderFactory();

        if (in_array($this->methodName, $this->methodNamesRequireDomainEntityTransfer)) {
            // Add method call with (new GlueResourceMethodConfigurationTransfer())->setAttributes(DomainEntityTransfer::class)
            return $builderFactory->args([
                $builderFactory->methodCall(
                    $builderFactory->new(static::CONFIGURATION_TRANSFER_FQCN),
                    'setAttributes',
                    $builderFactory->args([
                        $builderFactory->classConstFetch($this->resourceDataObjectName, 'class'),
                    ]),
                ),
            ]);
        }

        return $builderFactory->args([
            $builderFactory->new(static::CONFIGURATION_TRANSFER_FQCN),
        ]);
    }
}
