<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

use Symfony\Component\Yaml\Yaml;

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
    public const AUTO_FIXABLE = false;

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
     * @return CheckerValidatorRuleInterface
     */
    public function validate(array $spryk): CheckerValidatorRuleInterface
    {
        $this->errorMessages = [];
        $this->innerValidate($spryk);

        return $this;
    }

    /**
     * @param array $spryk
     *
     * @return void
     */
    abstract protected function innerValidate(array $spryk): void;

    /**
     * @return bool
     */
    public function isRuleAutofixable(): bool
    {
        return static::AUTO_FIXABLE;
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    protected function addErrorMessage(string $errorMessage)
    {
        $this->errorMessages[] = $errorMessage;
    }


    public function getRuleName(): string
    {
        return get_class($this);

    }

    public function fixPossibleIssue(array $checkedSpryk): void
    {
    }
}
