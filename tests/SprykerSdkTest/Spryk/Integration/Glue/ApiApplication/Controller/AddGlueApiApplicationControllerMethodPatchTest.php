<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\ApiApplication\Controller;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\GlueBackendApiClassNames;
use SprykerSdkTest\Module\GlueStorefrontApiClassNames;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group BackendApi
 * @griup StorefrontApi
 * @group AddGlueApiApplicationControllerMethodPatchTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationControllerMethodPatchTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiControllerMethodPatch(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--zedDomainEntity' => 'ZipZap',
            '--applicationType' => 'Backend',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_INDEX_CONTROLLER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_INDEX_CONTROLLER_TEST);
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_INDEX_CONTROLLER, 'patchAction');
    }

    /**
     * @return void
     */
    public function testAddsGlueStorefrontApiControllerMethodPatch(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--zedDomainEntity' => 'ZipZap',
            '--applicationType' => 'Storefront',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_INDEX_CONTROLLER);
        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_INDEX_CONTROLLER_TEST);
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_INDEX_CONTROLLER, 'patchAction');
    }
}
