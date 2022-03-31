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
    /**
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected SprykConfig $config;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface
     */
    protected ArgumentListBuilderInterface $argumentListBuilder;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface
     */
    protected SprykDefinitionDumperInterface $definitionDumper;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface $argumentListBuilder
     * @param \SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface $definitionDumper
     */
    public function __construct(
        SprykConfig $config,
        ArgumentListBuilderInterface $argumentListBuilder,
        SprykDefinitionDumperInterface $definitionDumper
    ) {
        $this->config = $config;
        $this->argumentListBuilder = $argumentListBuilder;
        $this->definitionDumper = $definitionDumper;
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

    /**
     * @param string $cacheFilePath
     *
     * @return array
     */
    protected function getCachedArgumentList(string $cacheFilePath): array
    {
        $argumentList = Yaml::parseFile($cacheFilePath);

        if ($argumentList === null) {
            return [];
        }

        return $argumentList;
    }
}
