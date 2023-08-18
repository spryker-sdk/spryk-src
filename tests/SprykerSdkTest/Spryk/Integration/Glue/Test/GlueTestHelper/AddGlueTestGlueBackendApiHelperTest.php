<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\Test\GlueTestHelper;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Test
 * @group GlueTestHelper
 * @group AddGlueTestGlueBackendApiHelperTest
 * Add your own group annotations below this line
 */
class AddGlueTestGlueBackendApiHelperTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueTestBackendApiHelper(): void
    {
        // Act
        $this->tester->run($this, []);

        // Assert
        $expectedFile = $this->tester->getProjectTestDirectory('Testify', 'Glue') . '_support/Helper/GlueBackendApiHelper.php';
        $this->assertFileExists($expectedFile);
    }
}
