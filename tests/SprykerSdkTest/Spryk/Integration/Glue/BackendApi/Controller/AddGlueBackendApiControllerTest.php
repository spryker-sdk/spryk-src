<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group AddGlueBackendApiControllerTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiControllerTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiController(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--module' => 'FooBarBackendApi',
        ]);

        $this->tester->assertClassOrInterfaceExists(ClassName::GLUE_BACKEND_API_CONTROLLER);
    }
}
