<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Yves;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Yves
 * @group AddYvesWidgetTest
 * Add your own group annotations below this line
 */
class AddYvesWidgetTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsYvesWidgetFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--widget' => 'ZipZap',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::YVES_WIDGET, ClassName::YVES_ABSTRACT_WIDGET);
    }

    /**
     * @return void
     */
    public function testAddsYvesWidgetFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--widget' => 'ZipZap',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_YVES_WIDGET, ClassName::YVES_ABSTRACT_WIDGET);
    }

    /**
     * @return void
     */
    public function testAddsYvesWidgetFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
            '--widget' => 'ZipZap',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
            '--widget' => 'ZipZap',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_YVES_WIDGET, ClassName::YVES_WIDGET);
    }
}
