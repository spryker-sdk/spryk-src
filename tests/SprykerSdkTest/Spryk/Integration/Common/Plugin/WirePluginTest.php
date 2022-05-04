<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common\Plugin;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Common
 * @group WirePluginTest
 * Add your own group annotations below this line
 */
class WirePluginTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWiresPlugins(): void
    {
        // Create DependencyProvider
        $arguments = [
            '--organization' => 'Spryker',
            '--application' => 'Zed',
            '--module' => 'FooBar',
            '--output' => 'array',
        ];

        $this->tester->runSpryk('AddDependencyProvider', $arguments);

        // Add method to DependencyProvider
        $arguments = [
            '--organization' => 'Spryker',
            '--application' => 'Zed',
            '--module' => 'FooBar',
            '--target' => '\Spryker\Zed\FooBar\FooBarDependencyProvider',
            '--method' => 'getZipZapPlugins',
            '--body' => 'return [];',
            '--output' => 'array',
        ];

        $this->tester->runSpryk('AddMethod', $arguments);

        // Run this Spryk for first Plugin
        $this->tester->run($this, [
            '--mode' => 'core',
            '--organization' => 'FooBar',
            '--target' => '\Spryker\Zed\FooBar\FooBarDependencyProvider::getZipZapPlugins',
            '--plugin' => '\Spryker\Zed\FooBarExtension\Dependency\Plugin\FooBarOnePlugin',
        ]);
        // Run this Spryk for second Plugin
        $this->tester->run($this, [
            '--mode' => 'core',
            '--organization' => 'FooBar',
            '--target' => '\Spryker\Zed\FooBar\FooBarDependencyProvider::getZipZapPlugins',
            '--plugin' => '\Spryker\Zed\FooBarExtension\Dependency\Plugin\FooBarTwoPlugin',
        ]);

        $expectedClass = '\Spryker\Zed\FooBar\FooBarDependencyProvider';
        $expectedMethod = 'getZipZapPlugins';
        $expectedBody = 'return [new FooBarOnePlugin(), new FooBarTwoPlugin()];';

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapPlugins');
        $this->tester->assertMethodBody($expectedClass, $expectedMethod, $expectedBody);
    }
}
