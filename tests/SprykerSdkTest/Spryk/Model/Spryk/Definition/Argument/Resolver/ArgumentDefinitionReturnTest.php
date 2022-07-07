<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Definition\Argument\Resolver;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizerInterface;
use SprykerSdkTest\SprykTester;

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
     * @var \SprykerSdkTest\SprykTester
     */
    protected SprykTester $tester;

    /**
     * @return array<string>
     */
    protected function getArgumentWithValueKey(): array
    {
        return [
            [
                ['value' => 'project'],
                ['value' => 'project'],
            ],
            [
                'project',
                ['value' => 'project'],
            ],
            [
                ['inherit' => 1],
                ['inherit' => 1],
            ],
            [
                ['inherit' => 1, 'value' => 'Foo'],
                ['inherit' => 1, 'value' => 'Foo'],
            ],
            [
                ['Foo', 'Bar'],
                ['value' => ['Foo', 'Bar']],
            ],
        ];
    }

    /**
     * @dataProvider getArgumentWithValueKey
     *
     * @param mixed $argumentDefinition
     * @param mixed $expectedResult
     * @return void
     */
    public function testNormalizeArgumentDefinitionReturnsCorrectValueWithAllPossibleCase($argumentDefinition, $expectedResult): void
    {
        // Arrange
        $argumentDefinitionNormalizer = $this->getArgumentDefinitionNormalizer();

        // Act
        $normalizedArguments = $argumentDefinitionNormalizer->normalizeArgumentDefinition($argumentDefinition);

        // Assert
        $this->assertSame($expectedResult, $normalizedArguments);
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizerInterface
     */
    protected function getArgumentDefinitionNormalizer(): ArgumentDefinitionNormalizerInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer\ArgumentDefinitionNormalizerInterface $argumentDefinitionNormalizer */
        $argumentDefinitionNormalizer = $this->tester->getClass(ArgumentDefinitionNormalizerInterface::class);

        return $argumentDefinitionNormalizer;
    }
}
