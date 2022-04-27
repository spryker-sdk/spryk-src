<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Common
 * @group AddDependencyBridgeTest
 * Add your own group annotations below this line
 */
class AddDependencyBridgeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsADependencyBridgeForAClientToZed(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--layer' => 'Business',
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => 'Client',
        ]);

        $this->tester->assertClassHasMethod(ClassName::ZED_CLIENT_BRIDGE, '__construct');
        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_CLIENT_BRIDGE_INTERFACE);
    }

    /**
     * @return void
     */
    public function testAddsADependencyBridgeForAFacadeToZed(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--layer' => 'Business',
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => 'Facade',
        ]);

        $this->tester->assertClassHasMethod(ClassName::ZED_FACADE_BRIDGE, '__construct');
        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_FACADE_BRIDGE_INTERFACE);
    }

    /**
     * @return void
     */
    public function testAddsADependencyBridgeForAServiceToZed(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--layer' => 'Business',
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => 'Service',
        ]);

        $this->tester->assertClassHasMethod(ClassName::ZED_SERVICE_BRIDGE, '__construct');
        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_SERVICE_BRIDGE_INTERFACE);
    }
}
