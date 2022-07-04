<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Communication;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Communication
 * @group AddZedCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class AddZedCommunicationFactoryTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedCommunicationFactoryFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::ZED_COMMUNICATION_FACTORY,
            'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory',
        );
    }

    /**
     * @return void
     */
    public function testAddsZedCommunicationFactoryFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ZED_COMMUNICATION_FACTORY,
            'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory',
        );
    }

    /**
     * @return void
     */
    public function testAddsZedCommunicationFactoryFileThatExtendsSameCoreBaseClass(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'core',
        ]);

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ZED_COMMUNICATION_FACTORY,
            'Spryker\Zed\FooBar\Communication\FooBarCommunicationFactory',
        );
    }
}
