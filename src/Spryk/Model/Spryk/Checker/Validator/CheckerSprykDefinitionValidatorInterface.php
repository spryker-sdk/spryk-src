<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

interface CheckerSprykDefinitionValidatorInterface
{
    /**
     * @param array $spryk
     *
     * @return array
     */
    public function validate(array $spryk): array;
}
