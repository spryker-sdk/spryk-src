<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Service;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Service
 * @group AddServiceDependencyProviderTest
 * Add your own group annotations below this line
 */
class AddServiceDependencyProviderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsServiceDependencyProviderFile(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::SERVICE_DEPENDENCY_PROVIDER,
            'Spryker\Service\Kernel\AbstractBundleDependencyProvider',
        );
    }

    /**
     * @return void
     */
    public function testAddsServiceDependencyProviderFileOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_SERVICE_DEPENDENCY_PROVIDER,
            'Spryker\Service\Kernel\AbstractBundleDependencyProvider',
        );
    }

    /**
     * @return void
     */
    public function testAddsServiceDependencyProviderFileThatExtendsSameCoreBaseClass(): void
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
            ClassName::PROJECT_SERVICE_DEPENDENCY_PROVIDER,
            'Spryker\Service\FooBar\FooBarDependencyProvider',
        );
    }
}
