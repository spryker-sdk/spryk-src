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
     * @dataProvider dataProvider
     *
     * @param string $dependencyType
     * @param string $application
     * @param string $expectedBridgeClass
     * @param string $expectedBridgeInterface
     * @param string|null $layer
     *
     * @return void
     */
    public function testAddsADependencyBridge(
        string $dependencyType,
        string $application,
        string $expectedBridgeClass,
        string $expectedBridgeInterface,
        ?string $layer = null
    ): void {
        $arguments = [
            '--module' => 'FooBar',
            '--application' => $application,
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => $dependencyType,
        ];

        if ($layer) {
            $arguments['--layer'] = $layer;
        }

        $this->tester->run($this, $arguments);

        $this->tester->assertClassOrInterfaceHasMethod($expectedBridgeClass, '__construct');
        $this->tester->assertClassOrInterfaceExists($expectedBridgeInterface);
    }

    /**
     * @return array<array<\string>>
     */
    public function dataProvider(): array
    {
        return [
            'from a Service to Zed' => ['Service', 'Zed', ClassName::ZED_SERVICE_BRIDGE, ClassName::ZED_SERVICE_BRIDGE_INTERFACE, 'Business'],
            'from a Facade to Zed' => ['Facade', 'Zed', ClassName::ZED_FACADE_BRIDGE, ClassName::ZED_FACADE_BRIDGE_INTERFACE, 'Business'],
            'from a Client to Zed' => ['Client', 'Zed', ClassName::ZED_CLIENT_BRIDGE, ClassName::ZED_CLIENT_BRIDGE_INTERFACE, 'Business'],
        ];
    }
}
