<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\Validation;

use Codeception\Test\Unit;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group Plugin
 * @group GlueApplication
 * @group AddGlueValidationTest
 * Add your own group annotations below this line
 */
class UpdateGlueValidationTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateValidationWithRequiredField(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Spryker',
            '--module' => 'FooBar',
            '--resource' => 'foo-bars',
            '--method' => 'post',
            '--field' => 'field',
            '--isRequired' => 'true',
            '--mode' => 'project',
        ]);

        $expectedFile = $this->tester->getVirtualDirectory() . 'src/Spryker/Glue/FooBar/Validation/foo-bars.validation.yaml';
        $expectedContent = [
            'foo-bars' => [
                'post' => [
                    'field' => ['Required'],
                ],
            ],
        ];

        $this->assertFileExists($expectedFile);
        $this->assertSame(Yaml::parseFile($expectedFile), $expectedContent);
    }

    /**
     * @return void
     */
    public function testUpdateValidationWithOptionalField(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Spryker',
            '--module' => 'FooBar',
            '--resource' => 'foo-bars',
            '--method' => 'post',
            '--field' => 'field',
            '--isRequired' => 'false',
            '--mode' => 'project',
        ]);

        $expectedFile = $this->tester->getVirtualDirectory() . 'src/Spryker/Glue/FooBar/Validation/foo-bars.validation.yaml';
        $expectedContent = [
            'foo-bars' => [
                'post' => [
                    'field' => ['Optional'],
                ],
            ],
        ];

        $this->assertFileExists($expectedFile);
        $this->assertSame(Yaml::parseFile($expectedFile), $expectedContent);
    }
}
