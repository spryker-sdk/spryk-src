<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Converts a resource name to model name
 *
 * Examples:
 * $this->filter('/foo') == 'Foo';
 * $this->filter('/foo/*') == 'Foo';
 */
class ResourceNameToModelNameFilter extends ResourceNameToModuleNameFilter
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'resourceNameToModelName';
}
