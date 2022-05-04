<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Yves\Dependency\Service;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Exception\SprykWrongDevelopmentLayerException;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Yves
 * @group Dependency
 * @group Service
 * @group AddYvesDependencyServiceBridgeInterfaceMethodTest
 * Add your own group annotations below this line
 */
class AddYvesDependencyServiceBridgeInterfaceMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @skip Skipped for further investigation prioritisation and fix.
     *
     * @return void
     */
    public function testAddsYvesDependencyServiceInterfaceMethod(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
            '--methods' => [
                'methodWithStringArgument',
                'methodWithArrayArgument',
                'methodReturnsVoid',
                'methodWithTransferInputAndTransferOutput',
                'methodWithDefaultNull',
                'methodWithDefaultArray',
                'methodWithoutDocBlockReturnType',
                'methodWithMultipleReturn',
                'methodWithMultipleReturnAndNullable',
                'methodWithNullableReturn',
            ],
        ]);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithStringArgument');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithArrayArgument');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodReturnsVoid');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithTransferInputAndTransferOutput');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithDefaultNull');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithDefaultArray');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithoutDocBlockReturnType');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithMultipleReturn');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithMultipleReturnAndNullable');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::YVES_SERVICE_BRIDGE_INTERFACE, 'methodWithNullableReturn');
    }

    /**
     * @return void
     */
    public function testAddYvesDependencyServiceInterfaceMethodFailsOnProjectLayer(): void
    {
        $this->expectException(SprykWrongDevelopmentLayerException::class);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
            '--methods' => [
                'methodWithStringArgument',
            ],
            '--mode' => 'project',
        ]);
    }
}
