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
    public function __construct(
        protected SprykDefinitionFinderInterface $sprykDefinitionFinder,
        protected SprykConfigurationLoaderInterface $configurationLoader,
        protected CheckerSprykDefinitionValidatorInterface $checkerSprykDefinitionValidator,
        protected SprykConfig $sprykConfig,
    ) {
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

    protected function validateSprykDefinition(array $spryk): array
    {
        return $this->checkerSprykDefinitionValidator->validate($spryk);
    }
}
