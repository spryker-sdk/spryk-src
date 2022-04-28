<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper;

use Symfony\Component\Yaml\Yaml;

class YmlDumper implements YmlDumperInterface
{
    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface> $resolvedFiles
     *
     * @return void
     */
    public function dump(array $resolvedFiles): void
    {
        foreach ($resolvedFiles as $resolvedYmlFile) {
            $resolvedYmlFile->setContent(Yaml::dump($resolvedYmlFile->getDecodedYml(), 10));
        }
    }
}
