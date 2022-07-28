<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\DescriptionExistingRule;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\NameExistingRule;

class CheckerSprykDefinitionValidator implements CheckerSprykDefinitionValidatorInterface
{
    /**
     * @var array<\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface>
     */
    protected array $postValidations;

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface> $postValidations
     */
    public function __construct(array $postValidations)
    {
        $this->postValidations = $postValidations;
    }

    /**
     * @param array $spryk
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface>
     */
    public function validate(array $spryk): array
    {
        $rules = [];

        foreach ($this->getRules() as $rule) {
            $rule->validate($spryk);
            $rules[$rule->getRuleName()] = $rule;
        }

        return $rules;
    }

    /**
     * @param array $sprykDetails
     *
     * @return array
     */
    public function postValidation(array $sprykDetails): array
    {
        foreach ($this->getPostValidations() as $postValidation) {
            $sprykDetails = $postValidation->validate($sprykDetails);
        }

        return $sprykDetails;
    }

    /**
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface>
     */
    protected function getPostValidations(): array
    {
        return $this->postValidations;
    }

    /**
     * @return array
     */
    protected function getRules(): array
    {
        return [
            new NameExistingRule(),
            new DescriptionExistingRule(),
        ];
    }
}
