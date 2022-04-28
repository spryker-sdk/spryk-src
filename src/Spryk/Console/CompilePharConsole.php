<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CompilePharConsole extends AbstractSprykConsole
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('spryk:compile')
            ->setDescription('Builds a PHAR for the Spryks.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Clean the cache...');
        $this->executeProcess(['php', 'bin/console', 'cache:clear', '-e', 'prod'], (string)getcwd());

        $output->writeln('Warm up the cache...');
        $this->executeProcess(['php', 'bin/console', 'cache:warmup', '-e', 'prod'], (string)getcwd());

        $output->writeln('Building argument list cache...');
        $this->executeProcess(['php', 'bin/console', 'spryk:build'], (string)getcwd());

        $output->writeln('Build the PHAR...');
        $this->executeProcess(['php', 'box.phar', 'compile'], getcwd() . '/compiler/build');

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $processDefinition
     * @param string $cwd
     *
     * @return void
     */
    protected function executeProcess(array $processDefinition, string $cwd): void
    {
        $process = new Process($processDefinition, $cwd);
        $process->start();
        $iterator = $process->getIterator($process::ITER_SKIP_ERR | $process::ITER_KEEP_OUTPUT);

        foreach ($iterator as $data) {
            echo $data . "\n";
        }
    }
}
