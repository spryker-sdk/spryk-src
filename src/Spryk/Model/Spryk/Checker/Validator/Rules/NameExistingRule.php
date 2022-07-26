<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

class NameExistingRule extends AbstractCheckerValidatorRule
{

    /**
     * @param array $spryk
     *
     * @return void
     */
    protected function innerValidate(array $spryk): void
    {
        if (!$this->isNamePropertyExists($spryk['definition'])) {
            $this->addErrorMessage('The name does not exist in the Spryk.');
        }

        if (!$this->isSprykNameEqualsFileName($spryk)) {
            $this->addErrorMessage('Spryk name does not equal file name.');
        }
    }

    /**
     * @param array $sprykDefinition
     *
     * @return bool
     */
    protected function isNamePropertyExists(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['name']);
    }

    /**
     * @return void
     */
    protected function isSprykNameEqualsFileName(array $spryk): bool
    {
        $filePath = explode('/', $spryk['path']);
        $fileName = substr(end($filePath), 0, strpos(end($filePath), "."));

        return isset($spryk['definition']['name']) && $fileName === $spryk['definition']['name'];
    }
}
