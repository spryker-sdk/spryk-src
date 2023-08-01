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
        $output->writeln('Building argument list cache...');
        $this->executeProcess(['php', 'bin/console', 'spryk:build']);

        $output->writeln('Clean the cache...');
        $this->executeProcess(['php', 'bin/console', 'cache:clear', '-e', 'prod', '--no-debug']);

        $output->writeln('Warm up the cache...');
        $this->executeProcess(['php', 'bin/console', 'cache:warmup', '-e', 'prod', '--no-debug']);

        $output->writeln('Build the PHAR');

        return static::CODE_SUCCESS;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array $processDefinition
     * @param string|null $cwd
     * @param array|null $env
     *
     * @return void
     */
    protected function executeProcess(array $processDefinition, ?string $cwd = null, ?array $env = []): void
    {
        $process = new Process($processDefinition, $cwd);
        $process->start();
        while (!$process->isRunning()) {
            sleep(1);
        }
        $iterator = $process->getIterator($process::ITER_SKIP_ERR | $process::ITER_KEEP_OUTPUT);

        foreach ($iterator as $data) {
            echo $data . "\n";
        }
    }
}
