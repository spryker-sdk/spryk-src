<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Filter;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Filter\ReturnValueToDocParameterFilter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Model
 * @group Spryk
 * @group Filter
 * @group ReturnValueToDocParamaterFilterTest
 * Add your own group annotations below this line
 */
class ReturnValueToDocParamaterFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testNullableReturnValueShouldHaveNull(): void
    {
        $inputParameters = '?string';
        $expectedResult = 'string|null';

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
        $filter = new ReturnValueToDocParameterFilter();
        $actualResult = $filter->filter($inputParameters);

        $this->assertSame($expectedResult, $actualResult);
    }
}
