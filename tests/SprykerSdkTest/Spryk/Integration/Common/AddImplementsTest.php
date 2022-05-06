<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Common
 * @group AddImplementsTest
 * Add your own group annotations below this line
 */
class AddImplementsTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsImplements(): void
    {
        $this->tester->runSpryk('AddClient', [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->runSpryk('AddClientInterface', [
            '--module' => 'ZipZap',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--target' => ClassName::CLIENT,
            '--interface' => 'Spryker\Client\ZipZap\ZipZapClientInterface',
        ]);

        $this->tester->assertClassHasImplement(ClassName::CLIENT, 'ZipZapClientInterface');
    }
}
