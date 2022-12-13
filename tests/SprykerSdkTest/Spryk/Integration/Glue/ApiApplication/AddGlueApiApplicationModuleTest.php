<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\ApiApplication;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group ApiApplication
 * @group AddGlueApiApplicationModuleTest
 * Add your own group annotations below this line
 */
class AddGlueApiApplicationModuleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsModuleBackend(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Backend',
        ]);

        $this->assertDirectoryExists($this->tester->getSprykerModuleDirectory('FooBarsBackendApi'));
    }

    /**
     * @return void
     */
    public function testAddsModuleStorefront(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBars',
            '--applicationType' => 'Storefront',
        ]);

        $this->assertDirectoryExists($this->tester->getSprykerModuleDirectory('FooBarsStorefrontApi'));
    }
}
