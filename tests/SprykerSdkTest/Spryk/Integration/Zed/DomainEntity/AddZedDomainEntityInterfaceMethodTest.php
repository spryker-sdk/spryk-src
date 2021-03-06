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
 * @group AddZedDomainEntityInterfaceMethodTest
 * Add your own group annotations below this line
 */
class AddZedDomainEntityInterfaceMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddZedDomainInterfaceEntityMethod(): void
    {
        $this->tester->run($this, [
            '--mode' => 'core',
            '--module' => 'FooBar',
            '--className' => 'FooBarEntity',
            '--modelMethod' => 'addSomething',
            '--input' => 'string $foo',
            '--output' => 'bool',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod('\Spryker\Zed\FooBar\Business\FooBarEntity\FooBarEntityInterface', 'addSomething');
    }

    /**
     * @return void
     */
    public function testAddZedDomainInterfaceEntityMethodOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--module' => 'FooBar',
            '--className' => 'FooBarEntity',
            '--modelMethod' => 'addSomething',
            '--input' => 'string $foo',
            '--output' => 'bool',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod('\Pyz\Zed\FooBar\Business\FooBarEntity\FooBarEntityInterface', 'addSomething');
    }
}
