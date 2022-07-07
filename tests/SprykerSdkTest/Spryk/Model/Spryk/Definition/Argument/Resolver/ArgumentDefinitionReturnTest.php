<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Definition\Argument\Resolver;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizer;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizerInterface;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Spryk
 * @group Definition
 * @group Argument
 * @group Resolver
 * @group NormalizeArgumentDefinitionReturnTest
 * Add your own group annotations below this line
 */
class ArgumentDefinitionReturnTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testNormalizeArgumentDefinitionReturnsCorrectValueWithArgumentWithValueKey(): void
    {
        // Arrange
        $argumentDefinitionNormalizer = $this->getArgumentDefinitionNormalizer();
        $normalizedArguments = [];

        $arguments = [
            'organization' => [
                'value' => 'Pyz',
            ],
            'body' => [
                'value' => [
                    'Foo',
                    'Bar',
                ],
            ],
        ];

        $expectedResult = [
            'organization' => [
                'value' => 'Pyz',
            ],
            'body' => [
                'value' => [
                    'Foo',
                    'Bar',
                ],
            ],
        ];

        // Act
        foreach ($arguments as $argumentName => $argumentDefinition) {
            $normalizedArguments[$argumentName] = $argumentDefinitionNormalizer->normalizeArgumentDefinition($argumentDefinition);
        }

        // Assert
        $this->assertSame($expectedResult, $normalizedArguments);
    }

    /**
     * @return void
     */
    public function testNormalizeArgumentDefinitionReturnsCorrectValueWithArgumentWithoutValueKey(): void
    {
        // Arrange
        $argumentDefinitionNormalizer = $this->getArgumentDefinitionNormalizer();
        $normalizedArguments = [];

        $arguments = [
            'organization' => 'Pyz',
            'mode' => 'project',
            'body' => [
                'Foo',
                'Bar',
            ],
        ];

        $expectedResult = [
            'organization' => [
                'value' => 'Pyz',
            ],
            'mode' => [
                'value' => 'project',
            ],
            'body' => [
                'value' => [
                    'Foo',
                    'Bar',
                ],
            ],
        ];

        // Act
        foreach ($arguments as $argumentName => $argumentDefinition) {
            $normalizedArguments[$argumentName] = $argumentDefinitionNormalizer->normalizeArgumentDefinition($argumentDefinition);
        }

        // Assert
        $this->assertSame($expectedResult, $normalizedArguments);
    }

    /**
     * @return void
     */
    public function testNormalizeArgumentDefinitionReturnsCorrectValueWithArgumentWithValueKeyAndWithOtherConfiguration(): void
    {
        // Arrange
        $argumentDefinitionNormalizer = $this->getArgumentDefinitionNormalizer();
        $normalizedArguments = [];
        $arguments = [
            'organization' => [
                'inherit' => 1,
                'value' => 'Pyz',
            ],
            'mode' => [
                'value' => 'project',
            ],
        ];

        $expectedResult = [
            'organization' => [
                'inherit' => 1,
                'value' => 'Pyz',
            ],
            'mode' => [
                'value' => 'project',
            ],
        ];

        // Act
        foreach ($arguments as $argumentName => $argumentDefinition) {
            $normalizedArguments[$argumentName] = $argumentDefinitionNormalizer->normalizeArgumentDefinition($argumentDefinition);
        }

        // Assert
        $this->assertSame($expectedResult, $normalizedArguments);
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizerInterface
     */
    protected function getArgumentDefinitionNormalizer(): ArgumentDefinitionNormalizerInterface
    {
        return new ArgumentDefinitionNormalizer();
    }
}
