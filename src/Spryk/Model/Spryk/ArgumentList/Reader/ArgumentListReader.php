<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader;

use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Yaml\Yaml;

class ArgumentListReader implements ArgumentListReaderInterface
{
    public function __construct(
        protected SprykConfig $config,
        protected ArgumentListBuilderInterface $argumentListBuilder,
        protected SprykDefinitionDumperInterface $definitionDumper,
    ) {
    }

    /**
     * @return array
     */
    public function getArgumentList(): array
    {
        $cacheFilePath = $this->config->getArgumentListReadPath();

        if ($cacheFilePath !== null) {
            return $this->getCachedArgumentList($cacheFilePath);
        }

        $sprykDefinitions = $this->definitionDumper->dump();

        return $this->argumentListBuilder->buildArgumentList($sprykDefinitions);
    }

    protected function getCachedArgumentList(string $cacheFilePath): array
    {
        $argumentList = Yaml::parseFile($cacheFilePath);

        if ($argumentList === null) {
            return [];
        }

        return $argumentList;
    }
}
