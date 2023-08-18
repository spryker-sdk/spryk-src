<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Communication\Plugin\Payment;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group AddMessageBrokerMessageHandlerPluginTest
 * Add your own group annotations below this line
 */
class AddMessageBrokerMessageHandlerPluginTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddMessageBrokerMessageHandlerPlugin(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Spryker',
            '--application' => 'Zed',
            '--module' => 'FooBar',
            '--messageName' => 'BazBat',
        ]);

        // MessageHandlerPlugin
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, 'onBazBat');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, 'handles');
        $this->tester->assertClassExtends(ClassName::ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, ClassName::ZED_ABSTRACT_PLUGIN);
        $this->tester->assertClassImplements(ClassName::ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, ClassName::ZED_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN_INTERFACE);

        // Facade method
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_FACADE, 'handleBazBat');
    }

    /**
     * @return void
     */
    public function testAddMessageBrokerMessageHandlerPluginOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--organization' => 'Pyz',
            '--application' => 'Zed',
            '--module' => 'FooBar',
            '--messageName' => 'BazBat',
            '--mode' => 'project',
        ]);

        // MessageHandlerPlugin
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, 'onBazBat');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, 'handles');
        $this->tester->assertClassExtends(ClassName::PROJECT_ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, ClassName::ZED_ABSTRACT_PLUGIN);
        $this->tester->assertClassImplements(ClassName::PROJECT_ZED_PLUGIN_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN, ClassName::ZED_MESSAGE_BROKER_MESSAGE_HANDLER_PLUGIN_INTERFACE);

        // Facade method
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::PROJECT_ZED_FACADE, 'handleBazBat');
    }
}
