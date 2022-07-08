<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group AddZedDependencyProviderTest
 * Add your own group annotations below this line
 */
class AddZedDependencyProviderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedDependencyProvider(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::ZED_DEPENDENCY_PROVIDER,
            ClassName::ZED_ABSTRACT_DEPENDENCY_PROVIDER,
        );
    }

    /**
     * @return void
     */
    public function testAddsZedDependencyProviderOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceExtends(
            ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER,
            ClassName::ZED_ABSTRACT_DEPENDENCY_PROVIDER,
        );
    }

    /**
     * @return void
     */
    public function testAddsZedDependencyProviderThatExtendsSameCoreBaseClass(): void
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
            ClassName::PROJECT_ZED_DEPENDENCY_PROVIDER,
            ClassName::ZED_DEPENDENCY_PROVIDER,
        );
    }
}
