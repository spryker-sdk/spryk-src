<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

interface CheckerValidatorRuleInterface
{

    public const ERRORS_KEY = 'errors';

    public const WARNINGS_RULE_KEY = 'warnings';

    /**
     * @return array
     */
    public function getErrorMessages(): array;

    /**
     * @param array $spryk
     *
     * @return CheckerValidatorRuleInterface
     */
    public function validate(array $spryk): CheckerValidatorRuleInterface;

    /**
     * @return bool
     */
    public function isRuleAutofixable(): bool;

    public function getRuleName(): string;

    public function fixPossibleIssue(array $checkedSpryk): void;
}
