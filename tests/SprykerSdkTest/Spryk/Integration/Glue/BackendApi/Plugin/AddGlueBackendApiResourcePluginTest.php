<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\Module\GlueBackendApiClassNames;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Plugin
 * @group AddGlueBackendApiResourcePluginTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiResourcePluginTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiResourcePlugin(): void
    {
//        // Create DependencyProvider
//        $arguments = [
//            '--organization' => 'Spryker',
//            '--application' => 'Glue',
//            '--module' => 'GlueBackendApiApplication',
//            '--output' => 'array',
//        ];
//
//        $this->tester->runSpryk('AddDependencyProvider', $arguments);
//
//        // Add method to DependencyProvider
//        $arguments = [
//            '--organization' => 'Spryker',
//            '--application' => 'Glue',
//            '--module' => 'GlueBackendApiApplication',
//            '--target' => '\Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider',
//            '--method' => 'getResourcePlugins',
//            '--body' => 'return [];',
//            '--output' => 'array',
//            '--withInterface' => false,
//        ];
//
//        $this->tester->runSpryk('AddMethod', $arguments);

        $this->tester->run($this, [
            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',

        ]);
        $expectedMethod = 'getResourcePlugins';
        $expectedBody = 'return [new FooBarsBackendApiResource()];';

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN);
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod);
        $this->tester->assertMethodBody(GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod, $expectedBody);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN, 'getType');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN, 'getController');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN, 'getDeclaredMethods');
    }
}
