<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use ReflectionClass;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class HttpResponseCodeToConstantNameFilter implements FilterInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $responseClassConstants = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'httpResponseCodeToConstantName';
    }

    /**
     * @param string $value
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function filter(string $value): string
    {
        if (count($this->responseClassConstants) === 0) {
            $this->populateResponseClassConstants();
        }

        $constantEntry = array_filter($this->responseClassConstants, static function ($el) use ($value) {
            return $el === (int)$value;
        });

        if (count($constantEntry) === 0) {
            throw new RuntimeException(sprintf(
                'Unable to find "%s" constant with values %s',
                Response::class,
                $value,
            ));
        }

        return sprintf('\%s::%s', Response::class, key($constantEntry));
    }

    /**
     * @return void
     */
    protected function populateResponseClassConstants(): void
    {
        $this->responseClassConstants = (new ReflectionClass(Response::class))->getConstants();
    }
}
