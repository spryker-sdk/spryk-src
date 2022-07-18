<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

interface CheckerSprykDefinitionValidatorInterface
{
    public function validate(array $sprykDefinition): array;
}
