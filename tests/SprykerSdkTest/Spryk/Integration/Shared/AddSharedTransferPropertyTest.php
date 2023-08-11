<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Shared;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Shared
 * @group AddSharedTransferPropertyTest
 * Add your own group annotations below this line
 */
class AddSharedTransferPropertyTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testDoesNotAddSharedTransferPropertyIfItAlreadyExists(): void
    {
        $this->tester->haveTransferSchemaFileWithTransferAndExistingProperty(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
        ]);

        $expectedXml = '<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">
  <transfer name="FooBar" strict="true">
    <property name="something" type="string" strict="true" />
  </transfer>

  <transfer name="FooBarItem" strict="true">
    <property name="testProperty" type="string" strict="true" />
  </transfer>

</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            $expectedXml,
        );
    }

    /**
     * @return void
     */
    public function testAddsSharedTransferProperty(): void
    {
        $this->tester->haveTransferSchemaFileWithTransfer(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
        ]);

        $expectedXml = '<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">
  <transfer name="FooBar" strict="true">
    <property name="something" type="string" strict="true" />
  </transfer>

  <transfer name="FooBarItem" strict="true">
    <property name="testProperty" type="string" strict="true" />
  </transfer>

</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            $expectedXml,
        );
    }

    /**
     * @group AddSharedTransferPropertyTestSingle
     *
     * This covers: --properties propertyA:string,propertyB:int
     *
     * @return void
     */
    public function testAddsSharedTransferPropertyFromMessagesProperties(): void
    {
        $this->tester->haveTransferSchemaFileWithTransfer(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--transfersProperties' => 'TransferA#propertyA:string,propertyB:int:singular;TransferB#propertyA:string,propertyB:int:singular',
        ]);

        $expectedXml = '<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">
  <transfer name="FooBar" strict="true">
    <property name="something" type="string" strict="true" />
  </transfer>

  <transfer name="FooBarItem" strict="true">
    </transfer>

  <transfer name="TransferA" strict="true">
    <property name="propertyA" type="string" strict="true" />
    <property name="propertyB" type="int" singular="singular" strict="true" />
  </transfer>

  <transfer name="TransferB" strict="true">
    <property name="propertyA" type="string" strict="true" />
    <property name="propertyB" type="int" singular="singular" strict="true" />
  </transfer>

</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            $expectedXml,
        );
    }

    /**
     * @return void
     */
    public function testAddsSharedTransferPropertyOnProjectLayer(): void
    {
        $this->tester->haveTransferSchemaFileWithTransfer(
            $this->tester->getProjectModuleDirectory('FooBar', 'Shared') . 'Transfer/foo_bar.transfer.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--mode' => 'project',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
        ]);

        $expectedXml = '<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">
  <transfer name="FooBar" strict="true">
    <property name="something" type="string" strict="true" />
  </transfer>

  <transfer name="FooBarItem" strict="true">
    <property name="testProperty" type="string" strict="true" />
  </transfer>

</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getProjectModuleDirectory('FooBar', 'Shared') . 'Transfer/foo_bar.transfer.xml',
            $expectedXml,
        );
    }
}
