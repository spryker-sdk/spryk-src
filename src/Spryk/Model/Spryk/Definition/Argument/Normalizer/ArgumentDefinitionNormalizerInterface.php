<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Normalizer;

interface ArgumentDefinitionNormalizerInterface
{
    /**
     * Prepares argument values from different types of syntax to work correctly later.
     *
     * @param mixed $argumentDefinition
     *
     * @return array
     */
    public function normalizeArgumentDefinition($argumentDefinition): array;
}
