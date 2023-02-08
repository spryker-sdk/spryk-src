<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Cleanup;

use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

interface CleanupRunnerInterface
{
    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface $resolved
     *
     * @return void
     */
    public function addForCleanup(ResolvedInterface $resolved): void;

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function runCleanup(SprykStyleInterface $style): void;
}
