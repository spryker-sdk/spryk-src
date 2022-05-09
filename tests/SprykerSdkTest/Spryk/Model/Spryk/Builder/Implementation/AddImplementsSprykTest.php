<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Spryk\Model\Spryk\Builder\Method;

use Codeception\Test\Unit;
use PhpParser\Node\Name;
use SprykerSdk\Spryk\Model\Spryk\Builder\Implementation\AddImplementsSpryk;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Builder
 * @group Implementation
 * @group AddImplementsSprykTest
 * Add your own group annotations below this line
 */
class AddImplementsSprykTest extends Unit
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
        require_once codecept_data_dir('../_support/Fixtures/Glue/GlueRestApiConventionExtension/Dependency/Plugin/RestResourceInterface.php');

        $sprykDefinition = $this->tester->getSprykDefinition([
            AddImplementsSpryk::ARGUMENT_TARGET => '\SprykerTest\GlueBackendApiResource',
            AddImplementsSpryk::ARGUMENT_INTERFACE => '\SprykerTest\RestResourceInterface',
        ]);

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Implementation\AddImplementsSpryk $addImplementsSpryk */
        $addImplementsSpryk = $this->tester->grabService(AddImplementsSpryk::class);
        $addImplementsSpryk->runSpryk($sprykDefinition);

        $fileResolver = $this->tester->getFileResolver();

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        $resolved = $fileResolver->resolve('\SprykerTest\GlueBackendApiResource');
        $nodeFinder = $this->tester->getNodeFinder();

        $this->assertInstanceOf(Name::class, $nodeFinder->findImplements($resolved->getClassTokenTree(), 'RestResourceInterface'));
    }
}
