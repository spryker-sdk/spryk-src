<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker;

interface SprykDefinitionCheckerInterface
{
    public function check(?string $sprykName): array;
}
