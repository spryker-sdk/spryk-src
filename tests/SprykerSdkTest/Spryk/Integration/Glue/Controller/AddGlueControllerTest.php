<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\Controller;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Controller
 * @group AddGlueControllerTest
 * Add your own group annotations below this line
 */
class AddGlueControllerTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueController(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'Bar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::GLUE_CONTROLLER, ClassName::GLUE_ABSTRACT_CONTROLLER);
    }

    /**
     * @return void
     */
    public function testAddsGlueControllerOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--controller' => 'Bar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_GLUE_CONTROLLER,
            ClassName::GLUE_ABSTRACT_CONTROLLER,
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueControllerThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
            '--controller' => 'Bar',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
            '--controller' => 'Bar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_GLUE_CONTROLLER,
            ClassName::GLUE_CONTROLLER,
        );
    }
}
