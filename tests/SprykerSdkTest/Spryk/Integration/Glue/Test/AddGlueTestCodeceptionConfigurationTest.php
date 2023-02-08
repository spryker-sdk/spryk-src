<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group AddGlueTestCodeceptionConfigurationTest
 * Add your own group annotations below this line
 */
class AddGlueTestCodeceptionConfigurationTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsGlueTestCodeceptionConfigurationUpdatesYml(): void
    {
        $this->tester->haveCodeceptionSuiteConfiguration(
            file_get_contents(codecept_data_dir('glueBackendApiCodeceptionConfiguration.yml')),
            'Spryker',
            'Glue',
            'FooBarsBackendApi',
        );
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--resource' => '/foo-bars',
        ]);

        $expectedFile = $this->tester->getSprykerModuleDirectory('FooBarsBackendApi') . 'tests/SprykerTest/Glue/FooBarsBackendApi/codeception.yml';
        $this->assertFileExists($expectedFile);

        // Ensure that an already existing element is not removed
        $this->tester->assertYmlArrayContainsElement('[suites][RestApi][modules][enabled]', 'SomeHelper', $expectedFile);

        // Ensure new elements are added
        $this->tester->assertYmlArrayContainsElement('[suites][RestApi][modules][enabled]', '\SprykerTest\Glue\Testify\Helper\GlueBackendApiHelper', $expectedFile);
    }
}
