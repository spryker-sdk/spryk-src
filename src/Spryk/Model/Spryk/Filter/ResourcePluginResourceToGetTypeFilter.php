<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;

/**
 * Example:
 * $this->filter(`/foo/{id}') === 'foo';
 * $this->filter(`/FooBar/{id}') === 'foo-bar';
 */
class ResourcePluginResourceToGetTypeFilter implements FilterInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'resourcePluginToGetType';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        $value = trim($value, '/');
        $valueFragments = explode('/', $value);

        $resourceType = array_shift($valueFragments);

        $filterChain = new FilterChain();
        $filterChain->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($resourceType);
    }
}
