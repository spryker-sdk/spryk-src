<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Cleanup;

use Exception;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface;
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
     * @var array<string, string>
     */
    protected array $pathsToRunCodeceptionBuild = [];

    /**
     * @var bool
     */
    protected bool $runTransferBuilders = false;

    /**
     * @var bool
     */
    protected bool $runPropelInstall = false;

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

        if ($resolved instanceof ResolvedYmlInterface) {
            $this->addResolvedJsonForCleanup($resolved);
        }

        if ($resolved instanceof ResolvedXmlInterface) {
            $this->runTransferBuilders = true;
            $this->runPropelInstall = true;
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
        $this->runCodeceptionBuildOnPaths($style);
        $this->runCodeSnifferOnModules($style);
        $this->runCodeSnifferOnPaths($style);
        $this->runTransferBuilders($style);
        $this->runPropelInstall($style);
        // @codeCoverageIgnoreEnd
    }

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

    protected function addResolvedJsonForCleanup(ResolvedYmlInterface $resolved): void
    {
        $pathToRunCodeceptionBuild = $this->getPathToRunCodeceptionBuild($resolved->getFilePath());

        if ($pathToRunCodeceptionBuild) {
            if (str_starts_with($pathToRunCodeceptionBuild, APPLICATION_ROOT_DIR)) {
                $pathToRunCodeceptionBuild = ltrim(substr($pathToRunCodeceptionBuild, strlen(APPLICATION_ROOT_DIR)), DIRECTORY_SEPARATOR);
            }
            $this->pathsToRunCodeceptionBuild[$pathToRunCodeceptionBuild] = $pathToRunCodeceptionBuild;
        }
    }

    protected function getPathToRunCodeceptionBuild(string $filePath): ?string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $filePath);
        $fileName = array_pop($pathFragments);

        if ($fileName !== 'codeception.yml') {
            return null;
        }

        // Project codeception.yml
        if (!array_search('vendor', $pathFragments)) {
            return $filePath;
        }

        // Core codeception.yml can be ignored in tests directory as those are included in the root codeception.yml
        if (array_search('tests', $pathFragments)) {
            return null;
        }

        return $filePath;
    }

    protected function getPathForCodeSniffer(string $filePath): string
    {
        $cutOffPosition = $this->getCutOffPosition($filePath) + 2;
        $pathFragments = explode(DIRECTORY_SEPARATOR, $filePath);

        $pathForCodeSniffer = implode(DIRECTORY_SEPARATOR, array_slice($pathFragments, 0, $cutOffPosition));

        if (str_starts_with($pathForCodeSniffer, APPLICATION_ROOT_DIR)) {
            $pathForCodeSniffer = substr($pathForCodeSniffer, strlen(APPLICATION_ROOT_DIR));
        }

        return ltrim($pathForCodeSniffer, DIRECTORY_SEPARATOR);
    }

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
     * @throws \Exception
     */
    protected function runCodeceptionBuildOnPaths(SprykStyleInterface $style): void
    {
        foreach ($this->pathsToRunCodeceptionBuild as $pathToRunCodeception) {
            $style->writeln(sprintf('Run Build Codeception command for <fg=green>%s</> config, this can take while...', $pathToRunCodeception));

            $process = new Process(['vendor/bin/codecept', 'build', '-c', $pathToRunCodeception], null, null, null, 600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new Exception($process->getErrorOutput());
            }

            echo $process->getOutput();
        }
    }

    protected function runCodeSnifferOnModules(SprykStyleInterface $style): void
    {
        foreach ($this->modulesToRunCodeSniffer as $moduleNameToFix) {
            $style->writeln(sprintf('Fixing Code Style for module <fg=green>%s</> this can take while...', $moduleNameToFix));

            $process = new Process(['vendor/bin/console', 'code:sniff:style', '-m', $moduleNameToFix, '-f'], null, null, null, 600);
            $process->run();

            echo $process->getOutput();
        }
    }

    protected function runCodeSnifferOnPaths(SprykStyleInterface $style): void
    {
        foreach ($this->pathsToRunCodeSniffer as $pathToRunCodeSniffer) {
            $style->writeln(sprintf('Fixing Code Style in path <fg=green>%s</> this can take while...', $pathToRunCodeSniffer));

            $process = new Process(['vendor/bin/console', 'code:sniff:style', $pathToRunCodeSniffer, '-f'], null, null, null, 600);
            $process->run();

            echo $process->getOutput();
        }
    }

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

    protected function runPropelInstall(SprykStyleInterface $style): void
    {
        if (!$this->runPropelInstall) {
            return;
        }

        $style->writeln('Run vendor/bin/console propel:install');

        $process = new Process(['vendor/bin/console', 'propel:install'], null, null, null, 180);
        $process->run();

        echo $process->getOutput();
    }
}
