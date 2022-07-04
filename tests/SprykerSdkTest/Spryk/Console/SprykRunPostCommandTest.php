<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerDumpAutoloadSprykCommand;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerReplaceGenerateSprykCommand;
use SprykerSdkTest\SprykConsoleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group SprykRunPostCommandTest
 * Add your own group annotations below this line
 */
class SprykRunPostCommandTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected SprykConsoleTester $tester;

    /**
     * @return void
     */
    public function testExecutePostCommandAfterCalledSpryk(): void
    {
        // Arrange
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommand',
            '--mode' => 'core',
        ];

        $this->tester->getMockWithExpectedNumberOfMethodCalls($this, ComposerDumpAutoloadSprykCommand::class, 1);

        // Act
        $tester->execute($arguments);

        // Assert
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/src');
    }

    /**
     * @return void
     */
    public function testExecuteAllPostCommandsAfterCalledSprykAndAllNestedSpryks(): void
    {
        // Arrange
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommandAndWithPreSprykWithPostCommand',
            '--mode' => 'core',
        ];

        $this->tester->getMockWithExpectedNumberOfMethodCalls($this, ComposerDumpAutoloadSprykCommand::class, 1);
        $this->tester->getMockWithExpectedNumberOfMethodCalls($this, ComposerReplaceGenerateSprykCommand::class, 1);

        // Act
        $tester->execute($arguments);

        // Assert
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/src');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBaz/src');
    }

    /**
     * @return void
     */
    public function testExecuteOnlyRunsUniquePostCommands(): void
    {
        // Arrange
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommandAndWithPreSprykWithSamePostCommand',
            '--mode' => 'core',
        ];

        $this->tester->getMockWithExpectedNumberOfMethodCalls($this, ComposerDumpAutoloadSprykCommand::class, 1);

        // Act
        $tester->execute($arguments);

        // Assert
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/src');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/BazBar/src');
    }
}
