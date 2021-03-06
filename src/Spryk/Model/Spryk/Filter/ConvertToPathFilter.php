<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Filter is used to convert a string containing backslashes to use DIRECTORY_SEPARATOR.
 *
 * Example:
 * $this->filter(`\Organization\Module\ClassName\') === 'Organization/Module/ClassName';
 */
class ConvertToPathFilter implements FilterInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'convertToPath';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, trim($value, '\\/'));
    }
}
