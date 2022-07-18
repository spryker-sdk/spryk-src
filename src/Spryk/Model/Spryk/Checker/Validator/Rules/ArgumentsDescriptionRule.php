<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

class ArgumentsDescriptionRule extends AbstractCheckerValidatorRule
{
    /**
     * @param array $spryk
     *
     * @return void
     */
    protected function innerValidate(array $spryk): void
    {
        if ($this->checkArgumentDescription($spryk['definition'])) {
            $this->addErrorMessage('Missing description of arguments.');
        }
    }

    protected function checkArgumentDescription(array $sprykDefinition)
    {
        foreach ($sprykDefinition['arguments'] as  $argumentDefinition) {
            if (!isset($argumentDefinition['description'])) {
                return false;
            }
        }

        return true;
    }

}
