<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper;

use SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper\ClassDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper\JsonDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper\XmlDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper\YmlDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedJsonInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface;

class FileDumper implements FileDumperInterface
{
    public function __construct(
        protected ClassDumperInterface $classDumper,
        protected YmlDumperInterface $ymlDumper,
        protected JsonDumperInterface $jsonDumper,
        protected XmlDumperInterface $xmlDumper,
    ) {
    }

    /**
     * @param iterable<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface> $resolvedFiles
     *
     * @return void
     */
    public function dumpFiles(iterable $resolvedFiles): void
    {
        $classFiles = [];
        $ymlFiles = [];
        $jsonFiles = [];
        $xmlFiles = [];

        foreach ($resolvedFiles as $resolvedFile) {
            if ($resolvedFile instanceof ResolvedClassInterface) {
                $classFiles[] = $resolvedFile;

                continue;
            }
            if ($resolvedFile instanceof ResolvedYmlInterface) {
                $ymlFiles[] = $resolvedFile;

                continue;
            }
            if ($resolvedFile instanceof ResolvedJsonInterface) {
                $jsonFiles[] = $resolvedFile;

                continue;
            }
            if ($resolvedFile instanceof ResolvedXmlInterface) {
                $xmlFiles[] = $resolvedFile;
            }
        }

        $this->classDumper->dump($classFiles);
        $this->ymlDumper->dump($ymlFiles);
        $this->jsonDumper->dump($jsonFiles);
        $this->xmlDumper->dump($xmlFiles);
    }
}
