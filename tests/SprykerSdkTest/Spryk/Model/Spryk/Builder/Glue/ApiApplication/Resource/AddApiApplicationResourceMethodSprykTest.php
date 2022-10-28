<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Spryk\Model\Spryk\Builder\Method;

use Codeception\Test\Unit;
use PhpParser\Node\Expr\MethodCall;
use SprykerSdk\Spryk\Model\Spryk\Builder\Glue\ApiApplication\Resource\AddApiApplicationResourceMethodSpryk;

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
 * @group AddApiApplicationResourceMethodSprykTest
 * Add your own group annotations below this line
 */
class AddApiApplicationResourceMethodSprykTest extends Unit
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
            AddApiApplicationResourceMethodSpryk::ARGUMENT_TARGET => '\SprykerTest\GlueBackendApiResource',
            AddApiApplicationResourceMethodSpryk::ARGUMENT_ORGANIZATION => 'Spryker',
            AddApiApplicationResourceMethodSpryk::ARGUMENT_MODULE => 'FooBar',
            AddApiApplicationResourceMethodSpryk::ARGUMENT_RESOURCE => 'ZipZap',
            AddApiApplicationResourceMethodSpryk::ARGUMENT_RESOURCE_DATA_OBJECT => '\Generated\Shared\Transfer\ZipZapTransfer',
            AddApiApplicationResourceMethodSpryk::ARGUMENT_METHOD => 'Post', // Should add a `setPost` method call
            AddApiApplicationResourceMethodSpryk::ARGUMENT_IS_BULK => false,
        ]);

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Glue\BackendApi\Resource\AddApiApplicationResourceMethodSpryk $addApiApplicationResourceMethodSpryk */
        $addApiApplicationResourceMethodSpryk = $this->tester->grabService(AddApiApplicationResourceMethodSpryk::class);
        $addApiApplicationResourceMethodSpryk->runSpryk($sprykDefinition);

        $fileResolver = $this->tester->getFileResolver();

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $fileResolver->resolve('\SprykerTest\GlueBackendApiResource');
        $nodeFinder = $this->tester->getNodeFinder();

        $this->assertInstanceOf(MethodCall::class, $nodeFinder->findMethodCallNode($resolved->getClassTokenTree(), 'setPost'));
    }
}
