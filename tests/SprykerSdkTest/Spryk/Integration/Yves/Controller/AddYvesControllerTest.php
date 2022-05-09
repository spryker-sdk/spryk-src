<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Yves\Controller;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Yves
 * @group Controller
 * @group AddYvesControllerTest
 * Add your own group annotations below this line
 */
class AddYvesControllerTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsYvesControllerFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => ClassName::YVES_CONTROLLER,
        ]);

        $targetClassFilePath = $this->tester->getSprykerShopModuleDirectory() . 'src/SprykerShop/Yves/FooBar/Controller/FooBarController.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Yves\Kernel\Controller\AbstractController');
    }

    /**
     * @return void
     */
    public function testAddsYvesControllerFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => ClassName::PROJECT_YVES_CONTROLLER,
            '--mode' => 'project',
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Yves') . 'Controller/FooBarController.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Yves\Kernel\Controller\AbstractController');
    }

    /**
     * @return void
     */
    public function testAddsYvesControllerFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
            '--controller' => ClassName::YVES_CONTROLLER,
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
            '--controller' => ClassName::YVES_CONTROLLER,
        ]);

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Yves') . 'Controller/FooBarController.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'SprykerShop\Yves\FooBar\Controller\FooBarController');
    }
}
