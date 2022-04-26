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
 * @group AddDependencyTypeToDependencyProviderTest
 * Add your own group annotations below this line
 */
class AddDependencyTypeToDependencyProviderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsFacadeDependencyToZedBusinessFactoryWithoutBridgeOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => 'Facade',
            '--layer' => 'Business',
        ]);

        $this->tester->assertClassHasMethod(ClassName::PROJECT_ZED_BUSINESS_FACTORY, 'getZipZapFacade');
        $this->tester->assertClassOrInterfaceDoesNotExist(ClassName::ZED_FACADE_BRIDGE);
        $this->tester->assertClassOrInterfaceDoesNotExist(ClassName::ZED_FACADE_BRIDGE_INTERFACE);
        $this->tester->assertClassHasMethod(ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER, 'provideBusinessLayerDependencies');
        $this->tester->assertClassHasMethod(ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER, 'addZipZapFacade');
        $this->tester->assertClassHasConstant(
            ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER,
            'FACADE_ZIP_ZAP',
            'FACADE_ZIP_ZAP',
            'public',
        );
    }

    /**
     * @return void
     */
    public function testAddsFacadeDependencyToZedBusinessFactoryWithBridgeOnCoreLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'core',
            '--module' => 'FooBar',
            '--organization' => 'Spryker',
            '--application' => 'Zed',
            '--dependentModule' => 'ZipZap',
            '--dependencyType' => 'Facade',
            '--layer' => 'Business',
        ]);

        $this->tester->assertClassHasMethod(ClassName::ZED_FACADE_BRIDGE, '__construct');
        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_FACADE_BRIDGE_INTERFACE);
        $this->tester->assertClassHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'getZipZapFacade');
        $this->tester->assertClassHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'provideBusinessLayerDependencies');
        $this->tester->assertClassHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapFacade');
        $this->tester->assertClassHasConstant(
            ClassName::ZED_DEPENDENCY_PROVIDER,
            'FACADE_ZIP_ZAP',
            'FACADE_ZIP_ZAP',
            'public',
        );
    }
}
