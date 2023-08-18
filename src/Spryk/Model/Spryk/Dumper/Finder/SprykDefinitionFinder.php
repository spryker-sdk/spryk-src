<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Dumper\Finder;

use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Finder\Finder;

class SprykDefinitionFinder implements SprykDefinitionFinderInterface
{
    public function __construct(protected SprykConfig $config)
    {
    }

    /**
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    public function find(): iterable
    {
        $finder = new Finder();
        $finder->in($this->config->getSprykDirectories())->files();

        return $finder;
    }
}
