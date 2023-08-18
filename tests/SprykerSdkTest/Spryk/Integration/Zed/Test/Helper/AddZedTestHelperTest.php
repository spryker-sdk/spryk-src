<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Test\Helper;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Test
 * @group Helper
 * @group AddZedTestHelperTest
 * Add your own group annotations below this line
 */
class AddZedTestHelperTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsZedTestHelperOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--organization' => 'Pyz',
            '--module' => 'FooBar',
        ]);

        $this->assertFileExists($this->tester->getVirtualDirectory() . 'tests/PyzTest/Zed/FooBar/_support/Helper/FooBarHelper.php');
    }
}
