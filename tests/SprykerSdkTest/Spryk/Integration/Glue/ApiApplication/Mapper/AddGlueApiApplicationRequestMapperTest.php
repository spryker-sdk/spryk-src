<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\BackendApi\Mapper;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\GlueBackendApiClassNames;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group BackendApi
 * @group Mapper
 * @group AddGlueBackendApiRequestMapperTest
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
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE);

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapGlueRequestTransferToFooBarCollectionDeleteCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapIdentifierToFooBarCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapIdentifierToFooBarCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapIdentifierToFooBarCollectionDeleteCriteriaTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapIdentifierToFooBarCollectionDeleteCriteriaTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER, 'mapFooBarTransferToFooBarCollectionRequestTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE, 'mapFooBarTransferToFooBarCollectionRequestTransfer');
    }
}
