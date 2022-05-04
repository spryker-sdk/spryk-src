<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Dependency\Facade;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Dependency
 * @group Facade
 * @group AddZedDependencyProviderDependencyFacadeMethodTest
 * Add your own group annotations below this line
 */
class AddZedDependencyFacadeDependencyProviderMethodTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsDependencyFacadeMethodToDependencyProvider(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapFacade');
    }

    /**
     * @return void
     */
    public function testAddsDependencyFacadeMethodToDependencyProviderOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--dependentModule' => 'ZipZap',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER, 'addZipZapFacade');
    }
}
