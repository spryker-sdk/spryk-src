<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Finder;

interface SprykTemplateFinderInterface
{
    /**
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    public function find(): iterable;
}
