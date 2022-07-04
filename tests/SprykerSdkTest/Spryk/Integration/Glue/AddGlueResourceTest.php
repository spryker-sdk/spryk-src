<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group AddGlueResourceTest
 * Add your own group annotations below this line
 */
class AddGlueResourceTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueResource(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--className' => 'Bar',
            '--mode' => 'core',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::GLUE_RESOURCE,
            'Spryker\Glue\Kernel\AbstractRestResource',
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueResourceOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--className' => 'Bar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_GLUE_RESOURCE,
            'Spryker\Glue\Kernel\AbstractRestResource',
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueResourceThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
            '--className' => 'Bar',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
            '--className' => 'Bar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_GLUE_RESOURCE,
            'Spryker\Glue\FooBar\BarResource',
        );
    }
}
