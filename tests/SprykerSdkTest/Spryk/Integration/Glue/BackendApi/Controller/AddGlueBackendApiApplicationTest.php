<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\BackendApi\Controller;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\GlueBackendApiClassNames;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group BackendApi
 * @group Controller
 * @group AddGlueBackendApiApplicationTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiApplicationTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiApplication(): void
    {
        $this->tester->run($this, [
            '--mode' => 'core',
            '--module' => 'FooBar',
            '--apiType' => 'JSON',
            '--applicationType' => 'Backend',
        ]);

        $foo = '';

        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_BOOTSTRAP);
//        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_APPLICATION_DEPENDENCY_PROVIDER);
//        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER);
//        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_REST_API_CONVENTION_DEPENDENCY_PROVIDER);
    }
}
