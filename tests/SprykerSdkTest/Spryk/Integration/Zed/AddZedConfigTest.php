<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group AddZedConfigTest
 * Add your own group annotations below this line
 */
class AddZedConfigTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
//    public function testAddsZedModuleConfig(): void
//    {
//        $this->tester->run($this, [
//            '--module' => 'FooBar',
//        ]);
//
//        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_CONFIG);
//    }
//
//    /**
//     * @return void
//     */
//    public function testAddsZedModuleConfigOnProjectLayer(): void
//    {
//        $this->tester->run($this, [
//            '--module' => 'FooBar',
//            '--mode' => 'project',
//        ]);
//
//        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_ZED_CONFIG);
//    }

    /**
     * @return void
     */
    public function testAddsZedModuleConfigOnProjectLevelAndExtendsCoreConfig(): void
    {
        // Add core Config
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);
        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_CONFIG);

        // Add project Config
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_ZED_CONFIG);
        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_ZED_CONFIG, ClassName::ZED_CONFIG);
    }
}
