<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator;

interface CheckerSprykDefinitionValidatorInterface
{
    /**
     * @param array $spryk
     *
     * @return array
     */
    public function validate(array $spryk): array;

    /**
     * @param array $sprykDetails
     *
     * @return array
     */
    public function postValidation(array $sprykDetails): array;
}
