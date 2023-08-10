<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\AsyncApi;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group AsyncApi
 * @group AddAsyncApiPublishMessageTest
 * Add your own group annotations below this line
 */
class AddAsyncApiPublishMessageTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsAsyncApiCodeOnProjectLevel(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--messageName' => 'ZipZap',
            '--channelName' => 'FooBarEvents',
            '--messages' => 'ZipZap#propertyA:string,propertyB:int',
            '--mode' => 'project',
        ]);

        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Communication\Plugin\MessageBroker\ZipZapMessageHandlerPlugin', 'handles');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Communication\Plugin\MessageBroker\ZipZapMessageHandlerPlugin', 'onZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\FooBarBusinessFactory', 'createZipZapMessageHandler');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\FooBarFacade', 'handleZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\FooBarFacadeInterface', 'handleZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\MessageBroker\ZipZapMessageHandler', 'handleZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\MessageBroker\ZipZapMessageHandlerInterface', 'handleZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\MessageBroker\MessageBrokerDependencyProvider', 'getMessageHandlerPlugins');

        // Tests for the Message receiving
        $this->tester->assertClassOrInterfaceHasMethod('PyzTest\AsyncApi\FooBar\FooBarTests\FooBarEvents\ZipZapTest', 'testZipZapMessageIsHandled');
        $this->tester->assertClassOrInterfaceHasMethod('PyzTest\AsyncApi\FooBar\Helper\FooBarHelper', 'haveZipZapTransfer');
    }

    /**
     * @return void
     */
//    public function testAddsMethodToFacadeInterfaceOnProjectLayer(): void
//    {
//        $this->tester->run($this, [
//            '--module' => 'FooBar',
//            '--facadeMethod' => 'addSomething',
//            '--input' => 'string $something',
//            '--output' => 'bool',
//            '--specification' => [
//                '- First specification line.',
//                '- Second specification line.',
//            ],
//            '--mode' => 'project',
//        ]);
//
//        $this->tester->assertClassOrInterfaceHasMethod('Pyz\Zed\FooBar\Business\FooBarFacadeInterface', 'addSomething');
//    }
//
//    /**
//     * @group testAddsCommentFacadeInterface
//     *
//     * @return void
//     */
//    public function testAddsCommentFacadeInterface(): void
//    {
//        $this->tester->run($this, [
//            '--module' => 'FooBar',
//            '--facadeMethod' => 'addSomething',
//            '--input' => 'string $something',
//            '--output' => 'bool',
//            '--specification' => [
//                '- First specification line.',
//                '- Second specification line.',
//            ],
//        ]);
//
//        $pathToFacadeInterface = $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Zed/FooBar/Business/FooBarFacadeInterface.php';
//        $facadeInterfaceContent = file_get_contents($pathToFacadeInterface);
//        $facadeInterfaceContent = ($facadeInterfaceContent) ?: '';
//
//        $this->assertRegExp('/\* Specification:/', $facadeInterfaceContent);
//        $this->assertRegExp('/\* - First specification line./', $facadeInterfaceContent);
//        $this->assertRegExp('/\* - Second specification line./', $facadeInterfaceContent);
//    }
}
