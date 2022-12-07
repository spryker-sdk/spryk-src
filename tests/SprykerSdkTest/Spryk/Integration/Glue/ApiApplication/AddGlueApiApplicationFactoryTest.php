<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\ApiApplication;

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
 * @group AddGlueApiApplicationFactoryTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationFactoryTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendFactory(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Backend',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_FACTORY);
        $this->tester->assertClassOrInterfaceExtends(
            GlueBackendApiClassNames::GLUE_BACKEND_API_FACTORY,
            GlueBackendApiClassNames::GLUE_ABSTRACT_FACTORY,
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueStorefrontFactory(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Storefront',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_BUSINESS_FACTORY);
        $this->tester->assertClassOrInterfaceExtends(
            GlueStorefrontApiClassNames::GLUE_STOREFRONT_API_BUSINESS_FACTORY,
            GlueStorefrontApiClassNames::GLUE_STOREFRONT_ABSTRACT_FACTORY,
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueBackendFactoryOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Backend',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_FACTORY);
        $this->tester->assertClassOrInterfaceExtends(
            GlueBackendApiClassNames::PROJECT_GLUE_BACKEND_API_FACTORY,
            GlueBackendApiClassNames::GLUE_ABSTRACT_FACTORY,
        );
    }

    /**
     * @return void
     */
    public function testAddsGlueStorefrontFactoryOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Storefront',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExists(GlueStorefrontApiClassNames::PROJECT_GLUE_STOREFRONT_API_BUSINESS_FACTORY);
        $this->tester->assertClassOrInterfaceExtends(
            GlueStorefrontApiClassNames::PROJECT_GLUE_STOREFRONT_API_BUSINESS_FACTORY,
            GlueStorefrontApiClassNames::GLUE_STOREFRONT_ABSTRACT_FACTORY,
        );
    }
}
