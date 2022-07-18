<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;


use SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface;

class CheckerSprykDefinitionValidator implements CheckerSprykDefinitionValidatorInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface[]
     */
    protected array $rules;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
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
            if (!$rule->validate($spryk)) {
                $invalidRules[] = $rule;
            }
        }

        return $invalidRules;
    }
}

