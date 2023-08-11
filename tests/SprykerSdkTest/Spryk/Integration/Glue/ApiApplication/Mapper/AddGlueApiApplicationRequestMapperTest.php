<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\ApiApplication\Mapper;

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
 * @group ApiApplication
 * @group Mapper
 * @group AddGlueApiApplicationRequestMapperTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationRequestMapperTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiRequestMapper(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--applicationType' => 'Backend',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE);

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCollectionRequestTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCollectionRequestTransfer');
    }

    /**
     * @return void
     */
    public function testAddsGlueStorefrontApiRequestMapper(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--applicationType' => 'Storefront',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER_INTERFACE);

        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCollectionRequestTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCollectionRequestTransfer');
    }
}
