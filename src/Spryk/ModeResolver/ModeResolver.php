<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\ModeResolver;

use SprykerSdk\Spryk\Exception\SprykWrongDevelopmentLayerException;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\SprykConfig;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

class ModeResolver implements ModeResolverInterface
{
    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @throws \SprykerSdk\Spryk\Exception\SprykWrongDevelopmentLayerException
     *
     * @return string
     */
    public function getMode(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): string
    {
        if (!$this->isValidMode($sprykDefinition, $style)) {
            $errorMessage = '`%s` spryk support `%s` development layer only.';

            throw new SprykWrongDevelopmentLayerException(
                sprintf($errorMessage, $sprykDefinition->getSprykName(), strtoupper($sprykDefinition->getMode())),
            );
        }

        $sprykMode = $style->getInput()->getOption(SprykConfig::NAME_ARGUMENT_MODE);

        return is_string($sprykMode) ? $sprykMode : $sprykDefinition->getMode();
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $sprykDefinition
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return bool
     */
    private function isValidMode(SprykDefinitionInterface $sprykDefinition, SprykStyleInterface $style): bool
    {
        $sprykModeArgument = $style->getInput()->getOption(SprykConfig::NAME_ARGUMENT_MODE);
        $sprykModeDefinition = $sprykDefinition->getMode();

        if ($sprykModeDefinition === 'both') {
            return true;
        }

        if ($sprykModeArgument === false || $sprykModeArgument === null) {
            return true;
        }

        return $sprykModeArgument === $sprykModeDefinition;
    }
}
