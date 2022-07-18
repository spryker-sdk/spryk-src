<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\CheckerSprykDefinitionValidatorInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface;
use SprykerSdk\Spryk\SprykConfig;

class SprykDefinitionChecker implements SprykDefinitionCheckerInterface
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface
     */
    protected SprykDefinitionFinderInterface $sprykDefinitionFinder;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface
     */
    protected SprykConfigurationLoaderInterface $configurationLoader;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\CheckerSprykDefinitionValidatorInterface
     */
    protected CheckerSprykDefinitionValidatorInterface $checkerSprykDefinitionValidator;
    /**
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected SprykConfig $sprykConfig;

    public function __construct(
        SprykDefinitionFinderInterface $sprykDefinitionFinder,
        SprykConfigurationLoaderInterface $configurationLoader,
        CheckerSprykDefinitionValidatorInterface $checkerSprykDefinitionValidator,
        SprykConfig $sprykConfig
    ) {
        $this->sprykDefinitionFinder = $sprykDefinitionFinder;
        $this->configurationLoader = $configurationLoader;
        $this->checkerSprykDefinitionValidator = $checkerSprykDefinitionValidator;
        $this->sprykConfig = $sprykConfig;
    }

    public function check(?string $sprykName): array
    {
        $validatedSprykDefinitions = [];
        $i = 0;
        $sprykFolder = $this->sprykConfig->getSprykRootDirectory() . 'config/spryk/' ;

        foreach ($this->sprykDefinitionFinder->find() as $fileInfo) {

            if ($i) {
                break;
            }


            $sprykName = str_replace('.' . $fileInfo->getExtension(), '', $fileInfo->getFilename());
            $sprykDefinition = $this->configurationLoader->loadSpryk($sprykName);

            $spryk = [
                'path' => $sprykFolder . $fileInfo->getRelativePathname(),
                'definition' => $sprykDefinition,
            ];

            $invalidRules = $this->validateSprykDefinition($spryk);
            $validatedSprykDefinitions['definitions'][$sprykName]['definition'] = $sprykDefinition;

            if($invalidRules) {
                $validatedSprykDefinitions['have_errors'] = true;
                $validatedSprykDefinitions['definitions'][$sprykName]['errors'] = $invalidRules;
            }

            $i++;

        }

        return $validatedSprykDefinitions;
    }

    protected function validateSprykDefinition(array $sprykDefinition): array
    {
        return $this->checkerSprykDefinitionValidator->validate($sprykDefinition);
    }
}
