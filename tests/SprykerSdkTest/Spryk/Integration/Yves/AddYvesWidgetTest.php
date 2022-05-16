<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Yves;

use Codeception\Test\Unit;

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

        $targetClassFilePath = $this->tester->getSprykerShopModuleDirectory() . 'src/SprykerShop/Yves/FooBar/Widget/ZipZapWidget.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Yves\Kernel\Widget\AbstractWidget');
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

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Yves') . 'Widget/ZipZapWidget.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'Spryker\Yves\Kernel\Widget\AbstractWidget');
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

        $targetClassFilePath = $this->tester->getProjectModuleDirectory('FooBar', 'Yves') . 'Widget/ZipZapWidget.php';

        $this->assertFileExists($targetClassFilePath);

        $this->tester->assertClassOrInterfaceExtends($targetClassFilePath, 'SprykerShop\Yves\FooBar\Widget\ZipZapWidget');
    }
}
