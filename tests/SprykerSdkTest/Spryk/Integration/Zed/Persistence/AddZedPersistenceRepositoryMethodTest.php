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
 * @group AddZedPersistenceRepositoryMethodTest
 * Add your own group annotations below this line
 */
class AddZedPersistenceRepositoryMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedPersistenceRepositoryMethod(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--repositoryMethod' => 'doSomething',
            '--input' => 'string $fooBar',
            '--output' => 'array',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_REPOSITORY, 'doSomething');
    }

    /**
     * @return void
     */
    public function testAddsZedPersistenceRepositoryMethodOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--repositoryMethod' => 'doSomething',
            '--input' => 'string $fooBar',
            '--output' => 'array',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_REPOSITORY, 'doSomething');
    }
}
