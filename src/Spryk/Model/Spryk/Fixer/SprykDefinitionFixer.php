<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Fixer;

use SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionChecker;
use SprykerSdk\Spryk\Model\Spryk\Fixer\Finder\SprykDefinitionFinderInterface;

class SprykDefinitionFixer implements SprykDefinitionFixerInterface
{
    public function __construct(protected SprykDefinitionFinderInterface $sprykDefinitionFinder, private SprykDefinitionChecker $sprykDefinitionChecker)
    {
    }

    /**
     * @return void
     */
    public function fix(): void
    {
        $checkedSpryks = $this->sprykDefinitionChecker->check();

        foreach ($checkedSpryks['definitions'] as $checkedSpryk) {
            foreach ($checkedSpryk['errors'] as $rule) {
                $rule->fixPossibleIssue($checkedSpryk);
            }
        }
    }
}
