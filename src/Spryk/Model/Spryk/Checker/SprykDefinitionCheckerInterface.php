<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker;

interface SprykDefinitionCheckerInterface
{
    /**
     * @return array
     */
    public function check(): array;
}
