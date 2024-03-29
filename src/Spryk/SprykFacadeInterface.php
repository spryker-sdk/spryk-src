<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk;

use SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

interface SprykFacadeInterface
{
    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface $sprykExecutorConfiguration
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function executeSpryk(
        SprykExecutorConfigurationInterface $sprykExecutorConfiguration,
        SprykStyleInterface $style,
    ): void;

    /**
     * @param int|null $level
     *
     * @return array
     */
    public function getSprykDefinitions(?int $level = null): array;

    /**
     * @param array $sprykDefinitions
     *
     * @return int
     */
    public function generateArgumentList(array $sprykDefinitions): int;

    /**
     * @return array
     */
    public function getArgumentList(): array;

    /**
     * @param string $sprykName
     * @param string|null $sprykMode
     *
     * @return array
     */
    public function getSprykDefinition(string $sprykName, ?string $sprykMode = null): array;

    /**
     * @return void
     */
    public function fixSprykDefinitions(): void;

    /**
     * @return array
     */
    public function checkSprykDefinitions(): array;
}
