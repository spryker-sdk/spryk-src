<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Plugin
 * @group AddBackendApiResourceMethodTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationResourceMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsMethodToBackendApiResourcePlugin(): void
    {
        $this->tester->run($this, [
            '--mode' => 'project',
            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',
            '--httpMethod' => 'Post',
            '--zedDomainEntity' => 'FooBar',
        ]);

        $this->tester->assertClassMethodHasMethodCall(ClassName::PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN, 'getDeclaredMethods', 'setPost');
    }
}
