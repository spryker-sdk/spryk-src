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
 * @group AddGlueBackendApiResponseMapperTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiResponseMapperTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiResponseMapper(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE);

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'mapFooBarCollectionTransferToGlueResponseTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'mapFooBarCollectionTransferToGlueResponseTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'mapFooBarCollectionTransferToSingleResourceGlueResponseTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'mapFooBarCollectionTransferToSingleResourceGlueResponseTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'mapFooBarCollectionResponseTransferToGlueResponseTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'mapFooBarCollectionResponseTransferToGlueResponseTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'mapFooBarCollectionResponseTransferToSingleResourceGlueResponseTransfer');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'mapFooBarCollectionResponseTransferToSingleResourceGlueResponseTransfer');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'addNotFoundError');
        $this->tester->assertClassOrInterfaceDoesNotHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'addNotFoundError');

        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER, 'addFooBarTransferToGlueResponse');
        $this->tester->assertClassOrInterfaceDoesNotHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE, 'addFooBarTransferToGlueResponse');
    }
}
