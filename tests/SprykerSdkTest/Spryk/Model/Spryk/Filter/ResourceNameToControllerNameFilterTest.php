<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Filter;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Filter\ResourceNameToControllerNameFilter;

class ResourceNameToControllerNameFilterTest extends Unit
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
        $filter = new ResourceNameToControllerNameFilter();
        $this->assertSame($expectedResult, $filter->filter($resource), 'Input: ' . $resource);
    }

    /**
     * @return array<array<\string>>
     */
    public function resourceNameProvider(): array
    {
        return [
            ['', 'Index'],
            ['/', 'Index'],
            ['foo', 'Index'],
            ['/foo', 'Index'],
            ['foo/bar', 'Bar'],
            ['/foo/bar', 'Bar'],
            ['foo/bar/baz', 'Bar'],
            ['/foo/bar/baz', 'Bar'],
            ['foo//bar', 'Index'],
            ['/foo//bar', 'Index'],
        ];
    }
}
