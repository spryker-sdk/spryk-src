<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Filter;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Filter\TypedArrayConvertFilter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Spryk
 * @group Filter
 * @group TypedArrayFilterTest
 * Add your own group annotations below this line
 */
class TypedArrayFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testTypedArrayShouldConvertTypedArrayToArray(): void
    {
        $inputParameters = 'string[] $foo';
        $expectedResult = 'array $foo';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldConvertTypedArraysToArrays(): void
    {
        $inputParameters = 'string[] $foo, int[] $bar';
        $expectedResult = 'array $foo, array $bar';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldConvertTypedArrayToArrayWithMixedValues(): void
    {
        $inputParameters = 'string[] $foo, int $bar';
        $expectedResult = 'array $foo, int $bar';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldConvertTypedArrayToArrayWithMultipleMixedValues(): void
    {
        $inputParameters = 'string[] $foo, int $bar, float[] $baz, iterable $qux';
        $expectedResult = 'array $foo, int $bar, array $baz, iterable $qux';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldConvertClassTypedArrayToArrayWithMultipleValues(): void
    {
        $inputParameters = '\stdClass[] $foo, \DateTime $bar, array $baz, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $qux';
        $expectedResult = 'array $foo, \DateTime $bar, array $baz, array $qux';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldConvertTypedArrayToArrayForReturnTypeHint(): void
    {
        $inputParameters = '\stdClass[]';
        $expectedResult = 'array';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayShouldNotConvertTypeHintWhenItsNotTypedArray(): void
    {
        $inputParameters = '\stdClass';
        $expectedResult = '\stdClass';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testInputParametersWithDefaultValueShouldRenderWithThisParameters(): void
    {
        $inputParameters = '?int $foo = null';
        $expectedResult = '?int $foo = null';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testMixedInputParametersWithDefaultValueShouldRenderWithThisParameters(): void
    {
        $inputParameters = '?\stdClass[] $foo = [], ?int $bar = null';
        $expectedResult = '?array $foo = [], ?int $bar = null';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testTypedArrayWithNullableValueShouldConvertToArrayWithNullableValue(): void
    {
        $inputParameters = '?string[] $foo';
        $expectedResult = '?array $foo';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testReturnTypeHintTypedArrayWithNullableValueShouldConvertToArrayWithNullableValue(): void
    {
        $inputParameters = '?string[]';
        $expectedResult = '?array';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithCollectionAndArraysShouldConvertToCollectionType(): void
    {
        $inputParameters = 'SomeCollection|string[]|int[]';
        $expectedResult = 'SomeCollection';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithCollectionAndArraysWithNullShouldConvertToCollectionType(): void
    {
        $inputParameters = '?SomeCollection|string[]|int[]|null';
        $expectedResult = '?SomeCollection';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithCollectionAndArraysWithQuestionNullShouldConvertToCollectionType(): void
    {
        $inputParameters = '?SomeCollection|string[]|int[]';
        $expectedResult = '?SomeCollection';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithArraysShouldConvertArrayType(): void
    {
        $inputParameters = 'string[]|int[]|bool[]';
        $expectedResult = 'array';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithArraysAndNullShouldConvertArrayType(): void
    {
        $inputParameters = 'string[]|int[]|bool[]|null';
        $expectedResult = '?array';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testComplexTypeWithArraysAndQuestionNullShouldConvertArrayType(): void
    {
        $inputParameters = '?string[]|int[]|bool[]';
        $expectedResult = '?array';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @return void
     */
    public function testMixedTypeShouldIgnore(): void
    {
        $inputParameters = 'mixed';
        $expectedResult = '';

        $this->assertFilterResult($inputParameters, $expectedResult);
    }

    /**
     * @param string $inputParameters
     * @param string $expectedResult
     *
     * @return void
     */
    protected function assertFilterResult(string $inputParameters, string $expectedResult): void
    {
        $filter = new TypedArrayConvertFilter();
        $actualResult = $filter->filter($inputParameters);

        $this->assertSame($expectedResult, $actualResult);
    }
}
