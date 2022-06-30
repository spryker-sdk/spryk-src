<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Closure;
use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;
use SprykerSdk\Spryk\Model\Spryk\Builder\Structure\StructureSpryk;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerDumpAutoloadSprykCommand;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerReplaceGenerateSprykCommand;

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
    protected $tester;

    /**
     * @return void
     */
    public function testExecutePostCommandAfterCalledSpryk(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommand',
            '--mode' => 'core',
        ];

        $currentCallIndex = 0;

        $this->verifyCallIndex(
            StructureSpryk::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Builder\\Structure\\StructureSpryk',
            'runSpryk',
            [0],
            $currentCallIndex,
        );

        $this->verifyCallIndex(
            ComposerDumpAutoloadSprykCommand::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Command\\ComposerDumpAutoloadSprykCommand',
            'execute',
            [1],
            $currentCallIndex,
        );

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testExecuteAllPostCommandsAfterCalledSprykAndAllNestedSpryks(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommandAndWithPreSprykWithPostCommand',
            '--mode' => 'core',
        ];

        $currentCallIndex = 0;

        $this->verifyCallIndex(
            StructureSpryk::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Builder\\Structure\\StructureSpryk',
            'runSpryk',
            [0, 1],
            $currentCallIndex,
        );

        $this->verifyCallIndex(
            ComposerDumpAutoloadSprykCommand::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Command\\ComposerDumpAutoloadSprykCommand',
            'execute',
            [2],
            $currentCallIndex,
        );

        $this->verifyCallIndex(
            ComposerReplaceGenerateSprykCommand::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Command\\ComposerReplaceGenerateSprykCommand',
            'execute',
            [3],
            $currentCallIndex,
        );

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testExecuteOnlyRunsUniquePostCommands(): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => 'SprykWithPostCommandAndWithPreSprykWithSamePostCommand',
            '--mode' => 'core',
        ];

        $this->verifyNumberOfCalls(
            ComposerDumpAutoloadSprykCommand::class,
            'SprykerSdk\\Spryk\\Model\\Spryk\\Command\\ComposerDumpAutoloadSprykCommand',
            'execute',
            1,
        );

        $tester->execute($arguments);
    }

    /**
     * @param string $class
     * @param string $service
     * @param string $method
     * @param array $expectedAtIndexes
     * @param int $currentCallIndex
     *
     * @return void
     */
    protected function verifyCallIndex(
        string $class,
        string $service,
        string $method,
        array $expectedAtIndexes,
        int &$currentCallIndex
    ): void {
        $sprykMock = $this->createPartialMock($class, [$method]);
        $sprykMock
            ->expects(static::any())
            ->method($method)
            ->willReturnCallback(
                $this->verifyCallIndexCallback($currentCallIndex, $expectedAtIndexes)
            );

        $this->tester->setDependency($service, $sprykMock);
    }

    /**
     * @param string $class
     * @param string $service
     * @param string $method
     * @param int $expectedNumberOfCalls
     *
     * @return void
     */
    protected function verifyNumberOfCalls(
        string $class,
        string $service,
        string $method,
        int $expectedNumberOfCalls
    ): void {
        $sprykMock = $this->createPartialMock($class, [$method]);
        $sprykMock
            ->expects(static::exactly($expectedNumberOfCalls))
            ->method($method);

        $this->tester->setDependency($service, $sprykMock);
    }

    /**
     * @param int $currentCallIndex
     * @param array<int> $expectedAtIndexes
     *
     * @return Closure
     */
    protected function verifyCallIndexCallback(int &$currentCallIndex, array $expectedAtIndexes): Closure
    {
        return function() use (&$currentCallIndex, $expectedAtIndexes) {
            $this->assertContains($currentCallIndex, $expectedAtIndexes, "Invalid call order.");
            $currentCallIndex++;
        };
    }
}
