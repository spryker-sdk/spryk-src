<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use Laminas\Filter\Word\DashToCamelCase;

/**
 * Converts a resource name to the appropriate controller name
 *
 * The scheme follows the same convention as used in Spryker's Zed controllers and actions
 *
 * @see https://docs.spryker.com/docs/scos/dev/back-end-development/zed/communication-layer/communication-layer.html#input-parameters
 *
 * Example:
 * $this->filter('/foo') == 'Index'
 * $this->filter('/foo/{id}') == 'Index'
 * $this->filter('/foo/{id}/something') == 'Something'
 * $this->filter('/foo/bar/baz') == 'Bar'
 */
class ResourceNameToControllerNameFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'resourceNameToControllerName';

    public function __construct(private DashToCamelCase $dashToCamelCase)
    {
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
        # resets indexes used further down
        $parts = array_values($parts);
        $name = 'Index';

        // Try third fragment as controller, when it is a placeholder, try second
        if (!empty($parts[2]) && !preg_match('/\{/', $parts[2])) {
            $name = $parts[2];
        }

        // Try second fragment as controller, when it is a placeholder, fallback to Index
        if (!empty($parts[1]) && !preg_match('/\{/', $parts[1])) {
            $name = $parts[1];
        }

        return $this->dashToCamelCase->filter($name);
    }
}
