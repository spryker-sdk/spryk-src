<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Converts a resource name to the appropriate controller name
 *
 * Example:
 * $this->filter('/foo/bar/baz') == 'Bar'
 * $this->filter('/foo') == 'Index'
 */
class ResourceNameToControllerNameFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'resourceNameToControllerName';

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
        $normalized = trim($value, '/');
        $parts = explode('/', $normalized);

        $name = 'Index';
        if (!empty($parts[1])) {
            $name = $parts[1];
        }

        return ucfirst($name);
    }
}
