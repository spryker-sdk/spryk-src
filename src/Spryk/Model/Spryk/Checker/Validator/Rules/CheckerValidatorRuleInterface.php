<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

interface CheckerValidatorRuleInterface
{
    /**
     * @return array
     */
    public function getErrorMessages(): array;

    /**
     * @param array $spryk
     *
     * @return bool
     */
    public function validate(array $spryk): bool;

    /**
     * @return bool
     */
    public function isRuleAutofixable(): bool;
}
