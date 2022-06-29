<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;
use SprykerSdk\Spryk\Model\Spryk\Builder\Structure\StructureSpryk;
use SprykerSdkTest\SprykConsoleTester;

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
    protected SprykConsoleTester $tester;

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

        $structureSprykMock = $this->createPartialMock(StructureSpryk::class, ['runSpryk']);
        $structureSprykMock->expects(static::once())->method('runSpryk');

        $this->tester->setDependency('SprykerSdk\\Spryk\\Model\\Spryk\\Builder\\Structure\\StructureSpryk', $structureSprykMock);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testExecuteOnlyNotExcludedSpryks(): void
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

        $this->assertFileDoesNotExist($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/README.md');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/firstDirectory');
        $this->assertDirectoryExists($this->tester->getVirtualDirectory() . 'vendor/spryker/spryker/Bundles/FooBar/secondDirectory');
    }
}
