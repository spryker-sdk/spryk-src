<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Client\Dependency\Client;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Exception\SprykWrongDevelopmentLayerException;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Client
 * @group Dependency
 * @group DependencyClient
 * @group AddClientDependencyClientBridgeMethodTest
 * Add your own group annotations below this line
 */
class AddClientDependencyClientBridgeMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @skip Skipped for further investigation prioritisation and fix.
     *
     * @return void
     */
    public function testAddsClientDependencyClientBridgeMethods(): void
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
                'methodWithoutMethodReturnType',
                'methodWithoutDocBlockReturnType',
                'methodWithMultipleReturn',
                'methodWithMultipleReturnAndNullable',
                'methodWithNullableReturn',
            ],
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithStringArgument');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithArrayArgument');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodReturnsVoid');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithTransferInputAndTransferOutput');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithDefaultNull');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithDefaultArray');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithoutMethodReturnType');
        $this->tester->assertClassNotContains(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithoutMethodReturnType(): void');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithoutDocBlockReturnType');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithMultipleReturn');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithMultipleReturnAndNullable');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithNullableReturn');
    }

    /**
     * @return void
     */
    public function testAddClientDependencyClientBridgeMethodFailsOnProjectLayer(): void
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

    /**
     * @return void
     */
    public function testAddsClientDependencyClientBridgeMethodOnlyOnce(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
            '--methods' => [
                'methodWithStringArgument',
            ],
        ]);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithStringArgument');

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
            '--methods' => [
                'methodWithStringArgument',
            ],
        ]);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::CLIENT_CLIENT_BRIDGE, 'methodWithStringArgument');
    }
}
