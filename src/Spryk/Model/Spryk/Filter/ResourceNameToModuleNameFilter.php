<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use Laminas\Filter\Word\DashToCamelCase;

/**
 * Converts a resource name to the corresponding module name
 *
 * Examples:
 * $this->filter('/foo') === 'Foo';
 * $this->filter('/foo/*') === 'Foo';
 * $this->filter('/foo-bar/*') === 'FooBar';
 */
class ResourceNameToModuleNameFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'resourceNameToModuleName';

    /**
     * @var \Laminas\Filter\Word\DashToCamelCase
     */
    private DashToCamelCase $dashToCamelCase;

    /**
     * @param \Laminas\Filter\Word\DashToCamelCase $dashToCamelCase
     */
    public function __construct(DashToCamelCase $dashToCamelCase)
    {
        $this->dashToCamelCase = $dashToCamelCase;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::FILTER_NAME;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        $parts = array_filter(explode('/', $value));

        if (count($parts) === 0) {
            $parts = [''];
        }

        return $this->dashToCamelCase->filter(current($parts));
    }
}
