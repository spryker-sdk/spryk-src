<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group DomainEntity
 * @group AddZedDomainEntityTest
 * Add your own group annotations below this line
 */
class AddZedDomainEntityTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddZedDomainEntity(): void
    {
        $this->tester->run($this, [
            '--mode' => 'core',
            '--module' => 'FooBar',
            '--className' => 'FooBarEntity',
        ]);

        $this->assertFileExists($this->tester->getSprykerModuleDirectory() . 'src/Spryker/Zed/FooBar/Business/FooBarEntity/FooBarEntity.php');
        $this->assertFileExists($this->tester->getSprykerModuleDirectory() . 'src/Spryker/Zed/FooBar/Business/FooBarEntity/FooBarEntityInterface.php');
    }

    /**
     * @return void
     */
    public function testAddZedDomainEntityOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--className' => 'FooBarEntity',
        ]);

        $this->assertFileExists($this->tester->getProjectModuleDirectory() . 'Business/FooBarEntity/FooBarEntity.php');
        $this->assertFileExists($this->tester->getProjectModuleDirectory() . 'Business/FooBarEntity/FooBarEntityInterface.php');
    }
}
