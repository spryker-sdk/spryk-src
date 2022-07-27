<?php

namespace SprykerSdk\Spryk\Model\Spryk\Fixer;

interface SprykDefinitionFixerInterface
{
    /**
     * @return array
     */
    public function fix(): array;
}
