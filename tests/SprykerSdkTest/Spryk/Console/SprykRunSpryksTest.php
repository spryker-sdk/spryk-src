<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group SprykRunSpryksTest
 * Add your own group annotations below this line
 */
class SprykRunSpryksTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteSprykWrapperWithSpryks(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWrapperWithSpryks',
            '--mode' => 'core',
        ];

        $tester->execute($arguments);

        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/firstDirectory');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/secondDirectory');
    }

    /**
     * Tests that when a Spryk with the same arguments was already executed it will not be executed again.
     * It might happen in the whole chain of Spryks with pre and postSpryks that one defines exactly the same Spryk.
     * Running it only once is ensured because of performance reasons.
     *
     * @return void
     */
    public function testExecuteOnlyRunsUniqueSpryks(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWrapperWithSprykUsedMoreThanOnce',
            '--mode' => 'core',
        ];

        $tester->execute($arguments);

        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/firstDirectory');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/secondDirectory');
    }

    /**
     * @return void
     */
    public function testExecuteSprykWrapperWithSpryksAndExcludedSpryks(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWrapperWithSpryksAndExcludedSpryks',
            '--mode' => 'core',
        ];

        $tester->execute($arguments);

        $this->assertDirectoryDoesNotExist($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/firstDirectory');
        $this->assertDirectoryDoesNotExist($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/secondDirectory');
    }
}
