<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Persistence;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Persistence
 * @group AddZedPersistenceEntityManagerInterfaceMethodTest
 * Add your own group annotations below this line
 */
class AddZedPersistenceEntityManagerInterfaceMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedPersistenceRepositoryInterfaceMethod(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--entityManagerMethod' => 'doSomething',
            '--input' => 'string $fooBar',
            '--output' => 'array',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_ENTITY_MANAGER_INTERFACE, 'doSomething');
    }

    /**
     * @return void
     */
    public function testAddsZedPersistenceRepositoryInterfaceMethodOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--entityManagerMethod' => 'doSomething',
            '--input' => 'string $fooBar',
            '--output' => 'array',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_ENTITY_MANAGER_INTERFACE, 'doSomething');
    }
}
