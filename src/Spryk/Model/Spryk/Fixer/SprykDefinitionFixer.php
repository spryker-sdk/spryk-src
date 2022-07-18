<?php

namespace SprykerSdk\Spryk\Model\Spryk\Fixer;

use SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionChecker;
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

    public function fix(?string $sprykName): array
    {
        if($sprykName) {

        }

        $checkedSpryks = $this->sprykDefinitionChecker->check($sprykName);

        foreach ($checkedSpryks as $checkedSpryk) {

        }



        $fixed = [];


        return $fixed;
    }
}
