<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

abstract class AbstractCheckerValidatorRule implements CheckerValidatorRuleInterface
{
    /**
     * @var array
     */
    public array $errorMessages = [];

    /**
     * @var bool
     */
    protected bool $isValid = true;

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
     * @return \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface
     */
    public function validate(array $spryk): CheckerValidatorRuleInterface
    {
        $this->errorMessages = [];
        $this->innerValidate($spryk);

        if ($this->getErrorMessages()) {
            $this->setIsValid(false);
        }

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
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    protected function addErrorMessage(string $errorMessage): void
    {
        $this->errorMessages[] = $errorMessage;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return static::class;
    }

    /**
     * @param array $checkedSpryk
     *
     * @return void
     */
    public function fixPossibleIssue(array $checkedSpryk): void
    {
    }
}
