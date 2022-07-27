<?php

namespace SprykerSdk\Spryk\Model\Spryk\Fixer;

use SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionChecker;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Fixer\Finder\SprykDefinitionFinderInterface;

class SprykDefinitionFixer implements SprykDefinitionFixerInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Fixer\Finder\SprykDefinitionFinderInterface
     */
    protected Finder\SprykDefinitionFinderInterface $sprykDefinitionFinder;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface
     */
    protected SprykConfigurationLoaderInterface $configurationLoader;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionChecker
     */
    private SprykDefinitionChecker $sprykDefinitionChecker;

    public function __construct(SprykDefinitionFinderInterface $sprykDefinitionFinder, SprykDefinitionChecker $sprykDefinitionChecker)
    {
        $this->sprykDefinitionFinder = $sprykDefinitionFinder;
        $this->sprykDefinitionChecker = $sprykDefinitionChecker;
    }

    /**
     * @return array
     */
    public function fix(): array
    {

        $checkedSpryks = $this->sprykDefinitionChecker->check();
        foreach ($checkedSpryks['definitions'] as $checkedSpryk) {
            foreach ($checkedSpryk['errors'] as $rule) {
                $rule->fixPossibleIssue($checkedSpryk);
            }

        }



        $fixed = [];


        return $fixed;
    }
}
