<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Client;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Client
 * @group AddClientFactoryTest
 * Add your own group annotations below this line
 */
class AddClientFactoryTest extends Unit
{
    /**
     * @var string
     */
    protected const SPRYK_NAME = 'AddClientFactory';

    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsClientFactoryFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::CLIENT_FACTORY, ClassName::CLIENT_ABSTRACT_FACTORY);
    }

    /**
     * @return void
     */
    public function testAddsClientFactoryFileOnProjectMode(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_CLIENT_FACTORY,
            ClassName::CLIENT_ABSTRACT_FACTORY,
        );
    }

    /**
     * @return void
     */
    public function testAddsClientFactoryFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(ClassName::PROJECT_CLIENT_FACTORY, ClassName::CLIENT_FACTORY);
    }
}
