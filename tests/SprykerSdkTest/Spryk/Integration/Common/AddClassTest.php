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
 * @group AddClassTest
 * Add your own group annotations below this line
 */
class AddClassTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsAClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--subDirectory' => 'Business',
            '--className' => 'FooBarFacade',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_FACADE);
    }

    /**
     * @return void
     */
    public function testAddsATestClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--application' => 'Zed',
            '--subDirectory' => 'Business',
            '--className' => 'FooBarFacade',
            '--isTestClass' => true,
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::ZED_FACADE_TEST);
    }
}
