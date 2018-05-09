<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Spryk\Model\Spryk\Definition\Builder;

use Spryker\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use Spryker\Spryk\Style\SprykStyleInterface;

interface SprykDefinitionBuilderInterface
{
    /**
     * @param string $sprykName
     * @param \Spryker\Spryk\Style\SprykStyleInterface $style
     *
     * @return \Spryker\Spryk\Model\Spryk\Definition\SprykDefinitionInterface
     */
    public function buildDefinition(string $sprykName, SprykStyleInterface $style): SprykDefinitionInterface;
}
