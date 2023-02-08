<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykDumpConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group SprykDumpTest
 * Add your own group annotations below this line
 */
class SprykDumpTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDumpsAllSpryks(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();

        $this->assertRegExp('/List of Spryk definitions/', $output);
    }

    /**
     * @return void
     */
    public function testDumpsSpecificSpryk(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykDumpConsole::ARGUMENT_SPRYK => 'AddModule',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertStringContainsString('Description of the AddModule Spryk', $output);
        $this->assertStringContainsString('The following arguments are required and you need to pass them', $output);
        $this->assertStringContainsString('The following arguments are optional and you can pass them when needed', $output);
        $this->assertStringContainsString('This Spryk does not have any preSpryk to be executed', $output);
        $this->assertStringContainsString('Post Spryks which are executed after the Spryk was running', $output);
        $this->assertStringContainsString('Use the following command to run this Spyk. You need to replace the placeholder values with your real value', $output);
        $this->assertStringContainsString('vendor/bin/spryk-run AddModule --module moduleValue --organization organizationValue', $output);
    }

    /**
     * @return void
     */
    public function testDumpsSpecificSprykWithoutRequiredArguments(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykDumpConsole::ARGUMENT_SPRYK => 'ExampleWithoutRequiredArguments',
        ];

        $tester->execute($arguments);

        $this->assertStringContainsString('This Spryk does not have any required arguments to be passed', $tester->getDisplay());
    }

    /**
     * @return void
     */
    public function testDumpsSpecificSprykWithoutOptionalArgumentsPrintsTableAsModeIsAlwaysAddedTOEverySpryk(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykDumpConsole::ARGUMENT_SPRYK => 'ExampleWithoutOptionalArguments',
        ];

        $tester->execute($arguments);

        // Check if we can see in the output the default mode
        $this->assertStringContainsString('project', $tester->getDisplay());
    }

    /**
     * @return void
     */
    public function testDumpsSpecificSprykPreSpryks(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykDumpConsole::ARGUMENT_SPRYK => 'ExampleWithPreSpryks',
        ];

        $tester->execute($arguments);

        $this->assertStringContainsString('AddModule', $tester->getDisplay());
    }

    /**
     * @return void
     */
    public function testDumpsSpecificSprykPostSpryks(): void
    {
        $command = $this->createSprykDumpConsole();
        $tester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            SprykDumpConsole::ARGUMENT_SPRYK => 'ExampleWithPostSpryks',
        ];

        $tester->execute($arguments);

        $this->assertStringContainsString('AddReadme', $tester->getDisplay());
        $this->assertStringContainsString('AddReadme', $tester->getDisplay());
    }

    /**
     * @return \SprykerSdk\Spryk\Console\SprykDumpConsole
     */
    protected function createSprykDumpConsole(): SprykDumpConsole
    {
        return $this->tester->getClass(SprykDumpConsole::class);
    }
}
