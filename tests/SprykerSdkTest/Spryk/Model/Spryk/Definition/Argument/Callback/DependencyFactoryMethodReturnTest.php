<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Definition\Argument\Callback;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Argument;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\DependencyFactoryMethodReturn;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollection;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Spryk
 * @group Definition
 * @group Argument
 * @group Callback
 * @group DependencyFactoryMethodReturnTest
 * Add your own group annotations below this line
 */
class DependencyFactoryMethodReturnTest extends Unit
{
    /**
     * @return void
     */
    public function testGetValueReturnsEmptySubNamespaceWhenInProjectModeAndDependencyTypeIsClient(): void
    {
        // Arrange
        $argumentCollection = new ArgumentCollection();
        $argumentCollection->addArgument((new Argument())->setName('mode')->setValue('project'));
        $argumentCollection->addArgument((new Argument())->setName('organization')->setValue('Pyz'));
        $argumentCollection->addArgument((new Argument())->setName('dependencyType')->setValue('Client'));
        $argumentCollection->addArgument((new Argument())->setName('dependentModule')->setValue('Foo'));

        // Act
        $dependencyFactoryMethodReturn = new DependencyFactoryMethodReturn();

        $value = $dependencyFactoryMethodReturn->getValue($argumentCollection, '');

        // Assert
        $this->assertSame('\Pyz\Client\Foo\FooClientInterface', $value);
    }

    /**
     * @return void
     */
    public function testGetValueReturnsBusinessSubNamespaceWhenInProjectModeAndDependencyTypeIsFacade(): void
    {
        // Arrange
        $argumentCollection = new ArgumentCollection();
        $argumentCollection->addArgument((new Argument())->setName('mode')->setValue('project'));
        $argumentCollection->addArgument((new Argument())->setName('organization')->setValue('Pyz'));
        $argumentCollection->addArgument((new Argument())->setName('dependencyType')->setValue('Facade'));
        $argumentCollection->addArgument((new Argument())->setName('dependentModule')->setValue('Foo'));

        // Act
        $dependencyFactoryMethodReturn = new DependencyFactoryMethodReturn();

        $value = $dependencyFactoryMethodReturn->getValue($argumentCollection, '');

        // Assert
        $this->assertSame('\Pyz\Zed\Foo\Business\FooFacadeInterface', $value);
    }
}
