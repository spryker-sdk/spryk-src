<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Common\Plugin;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Console\SprykRunConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Common
 * @group WirePluginTest
 * Add your own group annotations below this line
 */
class WirePluginTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWirePlugin(): void
    {
        $sprykName = 'AddZedDependencyPlugins';
        /** @var \SprykerSdk\Spryk\Console\SprykRunConsole $command */
        $command = $this->tester->getClass(SprykRunConsole::class);
        $tester = $this->tester->getConsoleTester($command, $sprykName);

        $arguments = [
            'command' => $command->getName(),
            SprykRunConsole::ARGUMENT_SPRYK => $sprykName,
            '--organization' => 'Spryker',
            '--module' => 'Ay',
            '--domainEntity' => 'Entity',
            '--type' => 'EntityPreCreate',
            '--output' => '\Spryker\Zed\AyExtension\Dependency\Plugin\Entity\Writer\EntityCreatePluginInterface[]'
        ];

        $tester->execute($arguments);

        $this->tester->run($this, [
            '--mode' => 'project',
            '--organization' => 'FooBar',
            '--targetFqcn' => '\Spryker\Zed\Ay\AyDependencyProvider',
            '--targetMethodName' => 'getEntityPreCreatePlugins',
            '--sourceFqcn' => 'PreCreatePluginOne',
            '--pluginInterface' => '\Spryker\Zed\AyExtension\Dependency\Plugin\Entity\Writer\EntityCreatePluginInterface',
        ]);

        $this->tester->run($this, [
            '--mode' => 'project',
            '--organization' => 'FooBar',
            '--targetFqcn' => '\Spryker\Zed\Ay\AyDependencyProvider',
            '--targetMethodName' => 'getEntityPreCreatePlugins',
            '--sourceFqcn' => 'PreCreatePluginTwo',
            '--pluginInterface' => '\Spryker\Zed\AyExtension\Dependency\Plugin\Entity\Writer\EntityCreatePluginInterface',
        ]);

        $expectedClass = '\FooBar\Zed\Ay\AyDependencyProvider';
        $expectedMethod = 'getEntityPreCreatePlugins';
        $expectedBody = 'return [new PreCreatePluginOne(), new PreCreatePluginTwo()];';

        $this->tester->assertClassExists($expectedClass);
        $this->tester->assertClassHasMethod($expectedClass, $expectedMethod);
        $this->tester->assertMethodBody($expectedClass, $expectedMethod, $expectedBody);
    }
}
