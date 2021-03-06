<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Communication\Controller;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Communication
 * @group Controller
 * @group AddZedCommunicationControllerTest
 * Add your own group annotations below this line
 */
class AddZedCommunicationControllerTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedControllerFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'Index',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::CONTROLLER_ZED, ClassName::ZED_ABSTRACT_CONTROLLER);
    }

    /**
     * @return void
     */
    public function testAddsZedControllerFileOnProjectLayerAndThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
            '--controller' => 'Index',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'Index',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_ZED_CONTROLLER, ClassName::CONTROLLER_ZED);
    }

    /**
     * @return void
     */
    public function testAddsZedControllerFileFromFullyQualifiedControllerClassName(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => ClassName::ZED_CONTROLLER,
        ]);

        $this->assertFileExists($this->tester->getSprykerModuleDirectory() . 'src/Spryker/Zed/FooBar/Communication/Controller/IndexController.php');
    }

    /**
     * @return void
     */
    public function testAddsZedControllerFileFromFullyQualifiedControllerClassNameOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => ClassName::PROJECT_ZED_CONTROLLER,
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ZED_CONTROLLER,
            ClassName::ZED_ABSTRACT_CONTROLLER,
        );
    }

    /**
     * @return void
     */
    public function testAddsZedControllerFileAndRemovesControllerSuffix(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'IndexController',
        ]);

        $this->assertFileExists($this->tester->getSprykerModuleDirectory() . 'src/Spryker/Zed/FooBar/Communication/Controller/IndexController.php');
    }

    /**
     * @return void
     */
    public function testAddsZedControllerFileAndRemovesControllerSuffixOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'IndexController',
            '--mode' => 'project',
        ]);

        $this->assertFileExists($this->tester->getProjectModuleDirectory() . 'Communication/Controller/IndexController.php');
    }
}
