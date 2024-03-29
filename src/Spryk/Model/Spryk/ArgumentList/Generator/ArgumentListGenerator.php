<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator;

use SprykerSdk\Spryk\Exception\FileGenerationException;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Yaml\Yaml;

class ArgumentListGenerator implements ArgumentListGeneratorInterface
{
    /**
     * @var string
     */
    protected $argumentListFilePath;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface
     */
    protected $argumentListBuilder;

    public function __construct(
        protected SprykConfig $config,
        ArgumentListBuilderInterface $argumentListBuilder,
    ) {
        $this->argumentListBuilder = $argumentListBuilder;
    }

    /**
     * @param array $sprykDefinitions
     *
     * @throws \SprykerSdk\Spryk\Exception\FileGenerationException
     *
     * @return int
     */
    public function generateArgumentList(array $sprykDefinitions): int
    {
        $sprykDefinitions = $this->argumentListBuilder->buildArgumentList($sprykDefinitions);

        $dataDump = Yaml::dump($sprykDefinitions, 2, 2);
        $dataDump = $this->getFileDescriptionComment() . $dataDump;

        $filePath = $this->config->getArgumentListWritePath();

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $result = file_put_contents($filePath, $dataDump);

        if ($result !== false) {
            return $result;
        }

        throw new FileGenerationException('File was not generated. Please check internal logs.');
    }

    protected function getFileDescriptionComment(): string
    {
        return '###'
            . PHP_EOL
            . '# This is auto-generated by the `build` command. It contains all arguments from all Spryks.'
            . PHP_EOL
            . '###'
            . PHP_EOL;
    }
}
