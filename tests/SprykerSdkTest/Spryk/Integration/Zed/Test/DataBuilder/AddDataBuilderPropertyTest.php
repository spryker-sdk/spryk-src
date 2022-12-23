<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Zed\Test\DataBuilder;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Test
 * @group DataBuilder
 * @group AddDataBuilderPropertyTest
 * Add your own group annotations below this line
 */
class AddDataBuilderPropertyTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testAddsDataBuilderPropertiesFromArrayWithSingleProperty(): void
    {
        $this->tester->haveDataBuilderSchema(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => [
                'testProperty:string',
            ],
        ]);

        $expectedXml = '<?xml version="1.0"?>
<transfers
    xmlns="spryker:databuilder-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:databuilder-01 http://static.spryker.com/databuilder-01.xsd"
>
    <transfer name="FooBarItem">
        <property name="testProperty" dataBuilderRule="word()" />
    </transfer>


</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            $expectedXml,
        );
    }

    /**
     * @return void
     */
    public function testAddsDataBuilderPropertiesWithMultipleProperties(): void
    {
        $this->tester->haveDataBuilderSchema(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            'FooBarItem',
        );

        $commandTester = $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty:string,anotherProperty:string',
        ]);

        $expectedXml = '<?xml version="1.0"?>
<transfers
    xmlns="spryker:databuilder-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:databuilder-01 http://static.spryker.com/databuilder-01.xsd"
>
    <transfer name="FooBarItem">
        <property name="testProperty" dataBuilderRule="word()" />
        <property name="anotherProperty" dataBuilderRule="word()" />
    </transfer>


</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            $expectedXml,
        );
    }

    /**
     * @return void
     */
    public function testAddsDataBuilderPropertyOnlyOnceAndDoesNotOverrideAnExistingProperty(): void
    {
        $this->tester->haveDataBuilderSchema(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
        ]);

        // Second run to cover some case when a property already exists.
        $commandTester = $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
        ]);

        $this->assertStringContainsString('Property by name testProperty already exists.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAddsDataBuilderAddsSpecifiedDataBuilder(): void
    {
        $this->tester->haveDataBuilderSchema(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => 'string',
            '--dataBuilderRule' => 'myRule',
        ]);

        $expectedXml = '<?xml version="1.0"?>
<transfers
    xmlns="spryker:databuilder-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:databuilder-01 http://static.spryker.com/databuilder-01.xsd"
>
    <transfer name="FooBarItem">
        <property name="testProperty" dataBuilderRule="myRule" />
    </transfer>


</transfers>';

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            $expectedXml,
        );
    }

    /**
     * @return array<array<\string>>
     */
    public function dataBuilder(): array
    {
        return [
            'string' => ['string', 'word()'],
            'int' => ['int', 'randomDigit()'],
            'bool' => ['bool', 'boolean()'],
            'array' => ['array', 'randomElements()'],
            'transfer' => ['Transfer', null],
        ];
    }

    /**
     * @dataProvider dataBuilder
     *
     * @param string $type
     * @param string|null $expectedDataBuilderRule
     *
     * @return void
     */
    public function testAddsDataBuilderProperty(string $type, ?string $expectedDataBuilderRule): void
    {
        $this->tester->haveDataBuilderSchema(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            'FooBarItem',
        );

        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--name' => 'FooBarItem',
            '--propertyName' => 'testProperty',
            '--propertyType' => $type,
        ]);

        $expectedProperty = '';

        if ($expectedDataBuilderRule) {
            $expectedProperty = sprintf('<property name="testProperty" dataBuilderRule="%s" />', $expectedDataBuilderRule);
        }

        $expectedTransferDefinition = '<transfer name="FooBarItem">
    </transfer>';

        if ($expectedProperty) {
            $expectedTransferDefinition = sprintf('<transfer name="FooBarItem">%s</transfer>', $expectedProperty);
        }

        $expectedXml = sprintf('<?xml version="1.0"?>
<transfers
    xmlns="spryker:databuilder-01"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="spryker:databuilder-01 http://static.spryker.com/databuilder-01.xsd"
>
    %s

</transfers>', $expectedTransferDefinition);

        $this->assertXmlStringEqualsXmlFile(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_data/foo_bar.databuilder.xml',
            $expectedXml,
        );
    }
}
