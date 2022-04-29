<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\BackendApi\Controller;

use Codeception\Test\Unit;

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
    public function testAddsGlueControllerAction(): void
    {
        $this->tester->run($this, [
            '--module' => 'GlueRestApiConvention',
            '--controller' => 'Bar',
            '--organization' => 'Spryker',
        ]);

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory('GlueRestApiConvention')
            . 'public/BackendApi/index.php',
        );

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory('GlueRestApiConvention')
            . 'src/Spryker/Glue/GlueApplication/Bootstrap/GlueBackendApiBootstrap.php',
        );

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory('GlueRestApiConvention')
            . 'src/Spryker/Glue/GlueRestApiConvention/GlueRestApiConventionDependencyProvider.php',
        );
    }
}
