<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation;

interface PostValidationInterface
{

    /**
     * @param array $sprykDefinitions
     * @return array
     */
    public function validate(array $sprykDefinitions): array;
}
