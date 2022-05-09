<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Client;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Client
 * @group AddClientDependencyProviderTest
 * Add your own group annotations below this line
 */
class AddClientDependencyProviderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsClientDependencyProviderFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $targetClassFilePath = $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Client/FooBar/FooBarDependencyProvider.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Client\Kernel\AbstractDependencyProvider');
    }

    /**
     * @return void
     */
    public function testAddsClientDependencyProviderFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Client') . 'FooBarDependencyProvider.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Client\Kernel\AbstractDependencyProvider');
    }

    /**
     * @return void
     */
    public function testAddsClientDependencyProviderFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Client') . 'FooBarDependencyProvider.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Client\FooBar\FooBarDependencyProvider');
    }
}
