<?php

namespace SprykerSdk\Spryk\Model\Spryk\Fixer;

interface SprykDefinitionFixerInterface
{
    public function fix(?string $sprykName): array;
}
