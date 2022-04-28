<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Spryker\Zed\Business\Validation;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Spryker
 * @group Zed
 * @group Business
 * @group Validation
 * @group AddDomainEntityValidatorTest
 */
class AddDomainEntityValidatorTest extends Unit
{
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testFilesExist(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--domainEntity' => 'ZipZap',
        ]);

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Validator/ZipZapValidator.php',
        );

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Validator/ZipZapValidatorInterface.php',
        );

        $this->tester->assertClassHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidator', 'validate');
        $this->tester->assertClassHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidator', 'validateCollection');

        $this->tester->assertClassHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidatorInterface', 'validate');
        $this->tester->assertClassHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidatorInterface', 'validateCollection');
    }
}
