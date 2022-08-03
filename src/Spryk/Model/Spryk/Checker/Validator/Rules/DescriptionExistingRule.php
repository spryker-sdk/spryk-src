<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

class DescriptionExistingRule extends AbstractCheckerValidatorRule
{
 /**
  * @param array $spryk
  *
  * @return void
  */
    protected function innerValidate(array $spryk): void
    {
        if (!$this->isSprykDescriptionExists($spryk['definition'])) {
            $this->addErrorMessage('Missing Spryk description.');
        }
    }

    /**
     * @param array $sprykDefinition
     *
     * @return bool
     */
    protected function isSprykDescriptionExists(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['description']) && strlen($sprykDefinition['description']);
    }
}
