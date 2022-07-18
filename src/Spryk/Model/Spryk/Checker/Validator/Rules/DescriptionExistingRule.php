<?php

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
            $this->addErrorMessage('isSprykDescriptionExists');
        }
    }

    protected function isSprykDescriptionExists(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['description']) && strlen($sprykDefinition['description']);
    }
}
