<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\CheckerSprykDefinitionValidatorInterface;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;
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

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface $sprykDefinitionFinder
     * @param \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface $configurationLoader
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Validator\CheckerSprykDefinitionValidatorInterface $checkerSprykDefinitionValidator
     * @param \SprykerSdk\Spryk\SprykConfig $sprykConfig
     */
    public function __construct(
        SprykDefinitionFinderInterface $sprykDefinitionFinder,
        SprykConfigurationLoaderInterface $configurationLoader,
        CheckerSprykDefinitionValidatorInterface $checkerSprykDefinitionValidator,
        SprykConfig $sprykConfig,
    ) {
        $this->sprykDefinitionFinder = $sprykDefinitionFinder;
        $this->configurationLoader = $configurationLoader;
        $this->checkerSprykDefinitionValidator = $checkerSprykDefinitionValidator;
        $this->sprykConfig = $sprykConfig;
    }

    /**
     * @return array
     */
    public function check(): array
    {
        $sprykDetails = [];

        foreach ($this->sprykDefinitionFinder->find() as $fileInfo) {
            $sprykName = str_replace('.' . $fileInfo->getExtension(), '', $fileInfo->getFilename());
            $sprykDefinition = $this->configurationLoader->loadSpryk($sprykName);

            $sprykDetails['definitions'][$sprykName] = [
                'path' => $fileInfo->getRealPath(),
                'definition' => $sprykDefinition,
                CheckerValidatorRuleInterface::ERRORS_KEY => [],
            ];

            $rules = $this->validateSprykDefinition($sprykDetails['definitions'][$sprykName]);

            foreach ($rules as $rule) {
                if ($rule->isValid() === false) {
                    $sprykDetails[CheckerValidatorRuleInterface::HAVE_ERRORS] = true;
                    $sprykDetails['definitions'][$sprykName][CheckerValidatorRuleInterface::ERRORS_KEY][] = $rule;
                }
            }
        }

        return $this->checkerSprykDefinitionValidator->postValidation($sprykDetails);
    }

    /**
     * @param array $spryk
     *
     * @return array
     */
    protected function validateSprykDefinition(array $spryk): array
    {
        return $this->checkerSprykDefinitionValidator->validate($spryk);
    }
}
