<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\DescriptionExistingRule;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\NameExistingRule;

class CheckerSprykDefinitionValidator implements CheckerSprykDefinitionValidatorInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[]
     */
    protected array $postValidations;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[] $postValidations
     */
    public function __construct(array $postValidations)
    {
        $this->postValidations = $postValidations;
    }

    /**
     * @param array $spryk
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface[]
     */
    public function validate(array $spryk): array
    {
        $invalidRules = [];

        foreach ($this->getRules() as $rule) {
            $rule->validate($spryk);

            if ($rule->getErrorMessages()) {
                $invalidRules[$rule->getRuleName()] = $rule;
            }
        }

        return $invalidRules;
    }

    /**
     * @param array $validatedSprykDefinitions
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
     * @return array|\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[]
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

