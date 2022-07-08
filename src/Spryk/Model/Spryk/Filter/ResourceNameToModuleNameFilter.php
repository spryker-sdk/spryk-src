<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Converts a resource name to the corresponding module name
 *
 * Examples:
 * $this->filter('/foo') === 'Foo';
 * $this->filter('/foo/*') === 'Foo';
 */
class ResourceNameToModuleNameFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'resourceNameToModuleName';

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

        return ucfirst(current($parts));
    }
}
