<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Cleanup;

use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface;
use SprykerSdk\Spryk\Style\SprykStyleInterface;
use Symfony\Component\Process\Process;

class CleanupRunner implements CleanupRunnerInterface
{
    /**
     * @var array<string, string>
     */
    protected array $modulesToRunCodeSniffer = [];

    /**
     * @var array<string, string>
     */
    protected array $pathsToRunCodeSniffer = [];

    /**
     * @var bool
     */
    protected bool $runTransferBuilders = false;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface $resolved
     *
     * @return void
     */
    public function addForCleanup(ResolvedInterface $resolved): void
    {
        if ($resolved instanceof ResolvedClassInterface) {
            $this->addResolvedClassForCleanup($resolved);
        }
        if ($resolved instanceof ResolvedXmlInterface) {
            $this->runTransferBuilders = true;
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    public function runCleanup(SprykStyleInterface $style): void
    {
        // For test performance reasons we do not run the cleanup commands.
        if (getenv('TESTING')) {
            return;
        }

        // @codeCoverageIgnoreStart
        $this->runCodeSnifferOnModules($style);
        $this->runCodeSnifferOnPaths($style);
        $this->runTransferBuilders($style);
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved
     *
     * @return void
     */
    protected function addResolvedClassForCleanup(ResolvedClassInterface $resolved): void
    {
        if ($resolved->getClassName()) {
            $classNameFragments = explode('\\', trim($resolved->getFullyQualifiedClassName(), '\\'));

            if (in_array($classNameFragments[0], ['Spryker', 'SprykerShop', 'SprykerEco'])) {
                $moduleNameToFix = sprintf('%s.%s', $classNameFragments[0], $classNameFragments[2]);
                $this->modulesToRunCodeSniffer[$moduleNameToFix] = $moduleNameToFix;

                return;
            }

            $pathToRunCodeSniffer = $this->getPathForCodeSniffer($resolved->getFilePath());
            $this->pathsToRunCodeSniffer[$pathToRunCodeSniffer] = $pathToRunCodeSniffer;
        }
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getPathForCodeSniffer(string $filePath): string
    {
        $cutOffPosition = $this->getCutOffPosition($filePath) + 2;
        $pathFragments = explode(DIRECTORY_SEPARATOR, $filePath);

        $pathForCodeSniffer = implode(DIRECTORY_SEPARATOR, array_slice($pathFragments, 0, $cutOffPosition));

        if (substr($pathForCodeSniffer, 0, strlen(APPLICATION_ROOT_DIR)) === APPLICATION_ROOT_DIR) {
            $pathForCodeSniffer = substr($pathForCodeSniffer, strlen(APPLICATION_ROOT_DIR));
        }

        return ltrim($pathForCodeSniffer, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $filePath
     *
     * @return int
     */
    protected function getCutOffPosition(string $filePath): int
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $filePath);
        $applicationNames = [
            'Yves',
            'Zed',
            'Glue',
            'Client',
            'Service',
            'Shared',
        ];

        foreach ($applicationNames as $applicationName) {
            $cutOffPosition = array_search($applicationName, $pathFragments);
            if ($cutOffPosition) {
                return $cutOffPosition;
            }
        }

        return count($pathFragments);
    }

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    protected function runCodeSnifferOnModules(SprykStyleInterface $style): void
    {
        foreach ($this->modulesToRunCodeSniffer as $moduleNameToFix) {
            $style->writeln(sprintf('Fixing Code Style for module <fg=green>%s</> this can take while...', $moduleNameToFix));

            $process = new Process(['vendor/bin/console', 'code:sniff:style', '-m', $moduleNameToFix, '-f'], null, null, null, 600);
            $process->run();

            echo $process->getOutput();
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    protected function runCodeSnifferOnPaths(SprykStyleInterface $style): void
    {
        foreach ($this->pathsToRunCodeSniffer as $pathToRunCodeSniffer) {
            $style->writeln(sprintf('Fixing Code Style in path <fg=green>%s</> this can take while...', $pathToRunCodeSniffer));

            $process = new Process(['vendor/bin/console', 'code:sniff:style', $pathToRunCodeSniffer, '-f'], null, null, null, 600);
            $process->run();

            echo $process->getOutput();
        }
    }

    /**
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface $style
     *
     * @return void
     */
    protected function runTransferBuilders(SprykStyleInterface $style): void
    {
        if (!$this->runTransferBuilders) {
            return;
        }

        $style->writeln('Run vendor/bin/console transfer:generate');

        $process = new Process(['vendor/bin/console', 'transfer:generate'], null, null, null, 180);
        $process->run();

        echo $process->getOutput();

        $style->writeln('Run vendor/bin/console transfer:databuilder:generate');

        $process = new Process(['vendor/bin/console', 'transfer:databuilder:generate'], null, null, null, 180);
        $process->run();

        echo $process->getOutput();
    }
}