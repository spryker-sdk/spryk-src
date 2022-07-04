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
 * @group AddZedPersistenceEntityManagerTest
 * Add your own group annotations below this line
 */
class AddZedPersistenceEntityManagerTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedEntityManagerFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::ENTITY_MANAGER,
            'Spryker\Zed\Kernel\Persistence\AbstractEntityManager',
        );
    }

    /**
     * @return void
     */
    public function testAddsZedEntityManagerFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ENTITY_MANAGER,
            'Spryker\Zed\Kernel\Persistence\AbstractEntityManager',
        );
    }

    /**
     * @return void
     */
    public function testAddsZedEntityManagerFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ENTITY_MANAGER,
            'Spryker\Zed\FooBar\Persistence\FooBarEntityManager',
        );
    }
}
