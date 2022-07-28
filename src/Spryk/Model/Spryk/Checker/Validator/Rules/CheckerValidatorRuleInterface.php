<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

interface CheckerValidatorRuleInterface
{
 /**
  * @var string
  */
    public const ERRORS_KEY = 'errors';

    /**
     * @var string
     */
    public const WARNINGS_RULE_KEY = 'warnings';

    /**
     * @var string
     */
    public const GENERAL_WARNINGS = 'general_warnings';

    /**
     * @var string
     */
    public const HAVE_WARNINGS = 'have_warnings';

    /**
     * @var string
     */
    public const HAVE_ERRORS = 'have_errors';


    /**
     * @return array
     */
    public function getErrorMessages(): array;

    /**
     * @param array $spryk
     *
     * @return $this
     */
    public function validate(array $spryk): self;

    /**
     * @return bool
     */
    public function isRuleAutofixable(): bool;

    /**
     * @return string
     */
    public function getRuleName(): string;

    /**
     * @param array $checkedSpryk
     *
     * @return void
     */
    public function fixPossibleIssue(array $checkedSpryk): void;
}
