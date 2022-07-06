<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Filter;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Filter\ResourceNameToModelNameFilter;

class ResourceNameToModelNameFilterTest extends Unit
{
    /**
     * @dataProvider resourceNameProvider
     *
     * @param string $resource
     * @param string $expectedResult
     *
     * @return void
     */
    public function testResourceNameConversion(string $resource, string $expectedResult): void
    {
        $filter = new ResourceNameToModelNameFilter();
        $this->assertSame($expectedResult, $filter->filter($resource), 'Input: ' . $resource);
    }

    /**
     * @return array<array<\string>>
     */
    public function resourceNameProvider(): array
    {
        return [
            ['', ''],
            ['customer', 'Customer'],
            ['/customer', 'Customer'],
            ['/customer/account', 'Account'],
            ['/customer/account/history', 'History'],
        ];
    }
}
