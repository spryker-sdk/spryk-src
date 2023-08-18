<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Shared;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Shared
 * @group AddSharedDomainEntityManipulationTransferDefinitionTest
 * Add your own group annotations below this line
 */
class AddSharedDomainEntityManipulationTransferDefinitionTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsSharedDomainEntityManipulationTransferDefinition(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--domainEntity' => 'FooBarModel',
        ]);

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
            '<?xml version="1.0"?>
<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

  <transfer name="FooBarModel" strict="true">
    <property name="idFooBarModel" type="int" strict="true"/>
    <property name="uuid" type="string" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionRequest" strict="true">
    <property name="isTransactional" type="bool" strict="true"/>
    <property name="fooBarModels" singular="fooBarModel" type="FooBarModel[]" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionResponse" strict="true">
    <property name="errors" singular="error" type="Error[]" strict="true"/>
    <property name="fooBarModels" singular="fooBarModel" type="FooBarModel[]" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionDeleteCriteria" strict="true">
    <property name="isTransactional" type="bool" strict="true"/>
    <property name="fooBarModelIds" singular="idFooBarModel" type="int[]" strict="true"/>
    <property name="uuids" singular="uuid" type="string[]" strict="true"/>
  </transfer>

  <transfer name="Error">
    <property name="message" type="string"/>
    <property name="entityIdentifier" type="string"/>
  </transfer>

</transfers>',
        );
    }

    /**
     * @return void
     */
    public function testAddsSharedDomainEntityManipulationTransferDefinitionOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--domainEntity' => 'FooBarModel',
            '--mode' => 'project',
        ]);

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getProjectModuleDirectory('FooBar', 'Shared') . 'Transfer/foo_bar.transfer.xml',
            '<?xml version="1.0"?>
<transfers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="spryker:transfer-01 http://static.spryker.com/transfer-01.xsd">

  <transfer name="FooBarModel" strict="true">
    <property name="idFooBarModel" type="int" strict="true"/>
    <property name="uuid" type="string" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionRequest" strict="true">
    <property name="isTransactional" type="bool" strict="true"/>
    <property name="fooBarModels" singular="fooBarModel" type="FooBarModel[]" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionResponse" strict="true">
    <property name="errors" singular="error" type="Error[]" strict="true"/>
    <property name="fooBarModels" singular="fooBarModel" type="FooBarModel[]" strict="true"/>
  </transfer>

  <transfer name="FooBarModelCollectionDeleteCriteria" strict="true">
    <property name="isTransactional" type="bool" strict="true"/>
    <property name="fooBarModelIds" singular="idFooBarModel" type="int[]" strict="true"/>
    <property name="uuids" singular="uuid" type="string[]" strict="true"/>
  </transfer>

  <transfer name="Error">
    <property name="message" type="string"/>
    <property name="entityIdentifier" type="string"/>
  </transfer>

</transfers>',
        );
    }
}
