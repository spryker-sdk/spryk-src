<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Filter;

/**
 * Filter is used to remove the variable type hint from a string
 * if it is present in the given one.
 *
 * Example:
 * $this->filter(`string $argument') === '$argument';
 * $this->filter(`\Generated\Shared\Transfer\FooTransfer $argument`) === '$argument';
 */
class RemoveParametersTypeFilter implements FilterInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'removeParametersTypeFilter';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter(string $value): string
    {
        $value = trim($value);
        preg_match_all('/\$[a-zA-Z]+/', $value, $argumentParts);

        return implode(', ', $argumentParts[0]);
    }
}
