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
 * @group AddGlueBackendApiRouteProviderPluginTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiCustomRouteProviderPluginTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiCustomRouteProviderPlugin(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',

        ]);
        $expectedMethod = 'getRouteProviderPlugins';
        $expectedBody = 'return [new FooBarsBackendApiRouteProviderPlugin()];';

        $this->tester->assertClassOrInterfaceExists(ClassName::PROJECT_GLUE_BACKEND_API_ROUTE_PROVIDER_PLUGIN);
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod);
        $this->tester->assertMethodBody(GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER, $expectedMethod, $expectedBody);
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_GLUE_BACKEND_API_ROUTE_PROVIDER_PLUGIN, 'addRoutes');
    }
}
