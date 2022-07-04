<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;
use SprykerSdkTest\SprykConsoleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group SprykRunPreSprykWithFlexibleArgumentsKeyValueTest
 * Add your own group annotations below this line
 */
class SprykRunPreSprykWithFlexibleArgumentsKeyValueTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected SprykConsoleTester $tester;

    /**
     * Tests that a value can be assigned directly to an argument when the argument doesn't need any further configuration.
     *
     * Example:
     *
     * argumentName: argumentValue
     *
     * @return void
     */
    public function testExecutesPreSprykWithoutValueKeyInArgument(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithoutValueKeyInArgument',
            '--mode' => 'core',
            '--organization' => 'Spryker',
        ];

        $tester->execute($arguments);

        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/TestFooBooModule/src');
    }

    /**
     * Tests that a value can be assigned in the arguments definition list.
     *
     * Example:
     *
     * argumentName:
     *     value: argumentValue
     *     otherArgumentOption: foo
     *
     * @return void
     */
    public function testExecutesPreSprykWithValueKeyInArgument(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithValueKeyInArgument',
            '--mode' => 'core',
            '--organization' => 'Spryker',
        ];

        $tester->execute($arguments);

        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/TestFooBooModule/src');
    }
}
