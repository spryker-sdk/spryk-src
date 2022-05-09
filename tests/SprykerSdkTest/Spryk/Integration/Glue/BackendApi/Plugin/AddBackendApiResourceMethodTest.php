<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Plugin
 * @group AddBackendApiResourceMethodTest
 * Add your own group annotations below this line
 */
class AddBackendApiResourceMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsBackendApiResourceMethod(): void
    {
        // Create DependencyProvider
        $arguments = [
            '--organization' => 'Spryker',
            '--application' => 'Glue',
            '--module' => 'GlueBackendApiApplication',
            '--output' => 'array',
        ];

        $this->tester->runSpryk('AddDependencyProvider', $arguments);

        // Add method to DependencyProvider
        $arguments = [
            '--organization' => 'Spryker',
            '--application' => 'Glue',
            '--module' => 'GlueBackendApiApplication',
            '--target' => '\Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider',
            '--method' => 'getResourcePlugins',
            '--body' => 'return [];',
            '--output' => 'array',
        ];

        $this->tester->runSpryk('AddMethod', $arguments);

        $this->tester->runSpryk('AddGlueBackendApiResourcePlugin', [
            '--module' => 'FooBar',
            '--organization' => 'Pyz',
            '--resource' => 'BarBaz',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--organization' => 'Pyz',
            '--resource' => 'BarBaz',
            '--method' => 'Post',
        ]);

        $this->tester->assertClassMethodHasMethodCall(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN, 'getDeclaredMethods', 'setPost');
    }
}
