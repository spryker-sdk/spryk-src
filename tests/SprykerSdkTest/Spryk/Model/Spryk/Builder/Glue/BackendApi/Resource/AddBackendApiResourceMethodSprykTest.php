<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Spryk\Model\Spryk\Builder\Method;

use Codeception\Test\Unit;
use PhpParser\Node\Expr\MethodCall;
use SprykerSdk\Spryk\Model\Spryk\Builder\Glue\BackendApi\Resource\AddBackendApiResourceMethodSpryk;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Builder
 * @group Glue
 * @group BackendApi
 * @group Resource
 * @group AddBackendApiResourceMethodSprykTest
 * Add your own group annotations below this line
 */
class AddBackendApiResourceMethodSprykTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddPostResource(): void
    {
        require_once codecept_data_dir('../_support/Fixtures/Glue/BackendApi/Resource/GlueBackendApiResource.php');

        $sprykDefinition = $this->tester->getSprykDefinition([
            AddBackendApiResourceMethodSpryk::ARGUMENT_TARGET => '\SprykerTest\GlueBackendApiResource',
            AddBackendApiResourceMethodSpryk::ARGUMENT_ORGANIZATION => 'Spryker',
            AddBackendApiResourceMethodSpryk::ARGUMENT_MODULE => 'FooBar',
            AddBackendApiResourceMethodSpryk::ARGUMENT_RESOURCE => 'ZipZap',
            AddBackendApiResourceMethodSpryk::ARGUMENT_RESOURCE_DATA_OBJECT => '\Generated\Shared\Transfer\ZipZapTransfer',
            AddBackendApiResourceMethodSpryk::ARGUMENT_METHOD => 'Post', // Should add a `setPost` method call
        ]);

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Glue\BackendApi\Resource\AddBackendApiResourceMethodSpryk $addBackendApiResourceMethodSpryk */
        $addBackendApiResourceMethodSpryk = $this->tester->grabService(AddBackendApiResourceMethodSpryk::class);
        $addBackendApiResourceMethodSpryk->runSpryk($sprykDefinition);

        $fileResolver = $this->tester->getFileResolver();

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $fileResolver->resolve('\SprykerTest\GlueBackendApiResource');
        $nodeFinder = $this->tester->getNodeFinder();

        $this->assertInstanceOf(MethodCall::class, $nodeFinder->findMethodCallNode($resolved->getClassTokenTree(), 'setPost'));
    }
}
