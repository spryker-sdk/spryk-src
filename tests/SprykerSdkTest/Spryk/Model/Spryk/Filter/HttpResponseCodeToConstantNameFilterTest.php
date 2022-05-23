<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Filter;

use Codeception\Test\Unit;
use SprykerSdkTest\SprykTester;

class HttpResponseCodeToConstantNameFilterTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykTester
     */
    protected SprykTester $tester;

    /**
     * @dataProvider getFilterTestData
     *
     * @param int $responseCode
     * @param string $expectedResult
     *
     * @return void
     */
    public function testFilterTransformDataProperly(int $responseCode, string $expectedResult): void
    {
        $this->assertSame($expectedResult, $this->tester->getHttpResponseCodeToConstantNameFilter()->filter($responseCode));
    }

    /**
     * @return array<array<mixed>>
     */
    public function getFilterTestData(): array
    {
        return [
            [200, '\Symfony\Component\HttpFoundation\Response::HTTP_OK'],
            [201, '\Symfony\Component\HttpFoundation\Response::HTTP_CREATED'],
            [204, '\Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT'],
            [301, '\Symfony\Component\HttpFoundation\Response::HTTP_MOVED_PERMANENTLY'],
            [302, '\Symfony\Component\HttpFoundation\Response::HTTP_FOUND'],
            [400, '\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST'],
            [401, '\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED'],
            [403, '\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN'],
            [404, '\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND'],
            [500, '\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR'],
        ];
    }
}
