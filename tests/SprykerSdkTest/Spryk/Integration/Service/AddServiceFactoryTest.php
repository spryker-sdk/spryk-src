<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Service;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Service
 * @group AddServiceFactoryTest
 * Add your own group annotations below this line
 */
class AddServiceFactoryTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsServiceFactoryFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $targetClassFilePath = $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Service/FooBar/FooBarFactory.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Service\Kernel\AbstractServiceFactory');
    }

    /**
     * @return void
     */
    public function testAddsServiceFactoryFileOnProjectMode(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Service') . 'FooBarFactory.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Service\Kernel\AbstractServiceFactory');
    }

    /**
     * @return void
     */
    public function testAddsServiceFactoryFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Service') . 'FooBarFactory.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Service\FooBar\FooBarFactory');
    }
}
