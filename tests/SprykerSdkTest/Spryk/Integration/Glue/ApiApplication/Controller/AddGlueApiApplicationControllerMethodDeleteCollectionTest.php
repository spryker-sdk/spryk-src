<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\ApiApplication\Controller;

use Codeception\Test\Unit;
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
 * @group BackendApi
 * @griup StorefrontApi
 * @group AddGlueApiApplicationControllerMethodDeleteCollectionTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationControllerMethodDeleteCollectionTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiControllerMethodDeleteCollection(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBarsBackendApi',
            '--resource' => '/foo-bars',
            '--zedDomainEntity' => 'ZipZap',
            '--applicationType' => 'Backend',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_INDEX_CONTROLLER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_DELETE_REST_API_CEST_TEST);
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_INDEX_CONTROLLER, 'deleteCollectionAction');
    }

    /**
     * @return void
     */
    public function testAddsGlueStorefrontApiControllerMethodDeleteCollection(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBarsStorefrontApi',
            '--resource' => '/foo-bars',
            '--zedDomainEntity' => 'ZipZap',
            '--applicationType' => 'Storefront',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_INDEX_CONTROLLER);
        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_DELETE_REST_API_RESOURCE_TEST);
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_INDEX_CONTROLLER, 'deleteCollectionAction');
    }
}
