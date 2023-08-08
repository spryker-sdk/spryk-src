<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Debug;

use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

interface DebugInterface
{
    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function setStyle(SprykStyleInterface $style): void;

    /**
     * @return bool
     */
    public function isDebug();

    /**
     * Prints based on the debug level information about the Spryk That Should be debugged.
     *
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     *
     * @return void
     */
    public function printDebug(SprykDefinitionInterface $sprykDefinition): void;
}
