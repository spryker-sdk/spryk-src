<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\CheckSprykDefinition;
use SprykerSdkTest\SprykConsoleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group CheckSprykDefinitionTest
 * Add your own group annotations below this line
 */
class CheckSprykDefinitionTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected SprykConsoleTester $tester;

    /**
     * @return array<string, array>
     */
    public function checkDefinitionDataProvider(): array
    {
        return [
            'check only' => [
                'arguments' => [],
                'expected return' => '/Spryk name does not equal file name./',
            ],
            'check only single spryk' => [
                'arguments' => ['spryk' => 'SprykWrapperWithSpryks'],
                'expected return' => '/Spryk name does not equal file name./',
            ],
            'check and fix' => [
                'arguments' => ['-f' => null],
                'expected return' => '/No validation errors found/',
            ],
        ];
    }

    /**
     * @dataProvider checkDefinitionDataProvider
     *
     * @param array<string, array> $arguments
     * @param string $expectedReturn
     *
     * @return void
     */
    public function testCheckDefinition(array $arguments, string $expectedReturn): void
    {
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(CheckSprykDefinition::class);
        $tester = $this->tester->getConsoleTester($command);

        $arguments['command'] = $command->getName();

        $tester->execute($arguments);

        $output = $tester->getDisplay();

        $this->assertRegExp($expectedReturn, $output);
    }
}
