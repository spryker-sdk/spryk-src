<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\Module\GlueBackendApiClassNames;
use SprykerSdkTest\Module\GlueStorefrontApiClassNames;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group ApiApplication
 * @group Plugin
 * @group AddGlueApiApplicationResourcePluginTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationResourcePluginTest extends Unit
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
        $this->tester->run($this, [
            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',
            '--applicationType' => 'Backend',
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

    /**
     * @return void
     */
    public function testAddsGlueStorefrontApiResourcePlugin(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',
            '--applicationType' => 'Storefront',
        ]);

        $expectedMethod = 'getResourcePlugins';
        $expectedBody = 'return [new FooBarsStorefrontApiResource()];';

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_GLUE_STOREFRONT_API_RESOURCE_PLUGIN);
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::PROJECT_GLUE_STOREFRONT_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod);
        $this->tester->assertMethodBody(GlueStorefrontApiClassNames::PROJECT_GLUE_STOREFRONT_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod, $expectedBody);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_STOREFRONT_API_RESOURCE_PLUGIN, 'getType');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_STOREFRONT_API_RESOURCE_PLUGIN, 'getController');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_STOREFRONT_API_RESOURCE_PLUGIN, 'getDeclaredMethods');
    }
}
