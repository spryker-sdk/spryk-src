<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

class CheckerSprykDefinitionValidator implements CheckerSprykDefinitionValidatorInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface[]
     */
    protected array $rules;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[]
     */
    protected array $postValidations;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface[] $rules
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[] $postValidations
     */
    public function __construct(array $rules, array $postValidations)
    {
        $this->rules = $rules;
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

        foreach ($this->rules as $rule) {
            $rule->validate($spryk);

            if ($rule->getErrorMessages()) {
                foreach ($rule->getErrorMessages() as $errorMessage) {
                    $invalidRules[get_class($rule)][] = $errorMessage;
                }
            }
        }

        return $invalidRules;
    }

    /**
     * @param array $validatedSprykDefinitions
     * @return array
     */
    public function postValidation(array $validatedSprykDefinitions): array
    {
        foreach ($this->getPostValidations() as $postValidation) {
            $validatedSprykDefinitions = $postValidation->validate($validatedSprykDefinitions);
        }

        return $validatedSprykDefinitions;
    }

    /**
     * @return array|\SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation\PostValidationInterface[]
     */
    protected function getPostValidations(): array
    {
        return $this->postValidations;
    }
}

