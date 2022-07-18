<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

abstract class AbstractCheckerValidatorRule implements CheckerValidatorRuleInterface
{
    /**
     * @var array
     */
    public array $errorMessages = [];

    protected static bool $isValid = true;

    /**
     * @var bool
     */
    public const AUTOFIXABLE = false;

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @param array $spryk
     *
     * @return bool
     */
    public function validate(array $spryk): bool
    {
        $this->innerValidate($spryk);

        if ($this->getErrorMessages()) {
            return false;
        }

        return true;
    }

    abstract protected function innerValidate(array $spryk): void;

    /**
     * @return bool
     */
    public function isRuleAutofixable(): bool
    {
        return static::AUTOFIXABLE;
    }

    /**
     * @param string $errorMessage
     * @return void
     */
    protected function addErrorMessage(string $errorMessage)
    {
        $this->errorMessages[] = $errorMessage;
    }

     public function fixPossibleIssue(): array
     {
         return [];
     }
}
