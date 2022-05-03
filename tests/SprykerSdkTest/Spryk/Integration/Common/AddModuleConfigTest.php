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
 * @group AddModuleConfigTest
 * Add your own group annotations below this line
 */
class AddModuleConfigTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsModuleConfigForZed(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForZedOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Zed',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_ZED_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForZedOnProjectLevelAndExtendsCoreClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_CONFIG);

        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Zed',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_ZED_CONFIG);
        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_ZED_CONFIG, ClassName::ZED_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForGlue(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Glue',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::GLUE_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForGlueOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Glue',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_GLUE_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForGlueOnProjectLevelAndExtendsCoreClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Glue',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::GLUE_CONFIG);

        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Glue',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_GLUE_CONFIG);
        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_GLUE_CONFIG, ClassName::GLUE_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForClient(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Client',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::CLIENT_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForClientOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Client',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_CLIENT_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForClientOnProjectLevelAndExtendsCoreClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Client',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::CLIENT_CONFIG);

        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Client',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_CLIENT_CONFIG);
        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_CLIENT_CONFIG, ClassName::CLIENT_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForService(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Service',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::SERVICE_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForServiceOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Service',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_SERVICE_CONFIG);
    }

    /**
     * @return void
     */
    public function testAddsModuleConfigForServiceOnProjectLevelAndExtendsCoreClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Service',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::SERVICE_CONFIG);
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--application' => 'Service',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_SERVICE_CONFIG);
        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_SERVICE_CONFIG, ClassName::SERVICE_CONFIG);
    }
}
