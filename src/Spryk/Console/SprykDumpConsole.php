<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use Exception;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SprykDumpConsole extends AbstractSprykConsole
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'spryk:dump';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Dumps a list of all Spryk definitions.';

    /**
     * @var string
     */
    public const ARGUMENT_SPRYK = 'spryk';

    /**
     * @var string
     */
    protected const OPTION_LEVEL = 'level';

    /**
     * @var string
     */
    protected const OPTION_LEVEL_SHORT = 'l';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(static::ARGUMENT_SPRYK, InputOption::VALUE_OPTIONAL, 'Name of a specific Spryk for which the options should be dumped.')
            ->addOption(
                static::OPTION_LEVEL,
                static::OPTION_LEVEL_SHORT,
                InputOption::VALUE_REQUIRED,
                'Spryk visibility level (1, 2, 3, all). By default = 1 (main spryk commands).',
                (string)SprykConfig::SPRYK_DEFAULT_DUMP_LEVEL,
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $level = $this->getLevelOption($input);

        $sprykName = current((array)$input->getArgument(static::ARGUMENT_SPRYK));
        if ($sprykName !== false) {
            $this->dumpSpryk($output, $sprykName);

            return static::CODE_SUCCESS;
        }

        $this->dumpAllSpryks($output, $level);

        return static::CODE_SUCCESS;
    }

    protected function getLevelOption(InputInterface $input): ?int
    {
        $level = current((array)$input->getOption(static::OPTION_LEVEL));

        return $level === 'all' ? null : (int)$level;
    }

    protected function dumpAllSpryks(OutputInterface $output, ?int $level): void
    {
        $sprykDefinitions = $this->getFacade()->getSprykDefinitions($level);
        $sprykDefinitions = $this->formatSpryks($sprykDefinitions);

        $output->writeln('List of Spryk definitions:');
        $this->printTable($output, ['Spryk name', 'Description', 'Arguments'], $sprykDefinitions);
    }

    protected function dumpSpryk(OutputInterface $output, string $sprykName): void
    {
        $sprykDefinition = $this->getFacade()->getSprykDefinition($sprykName);

        $output->writeln(sprintf('<fg=green>Description of the <fg=yellow>%s</> Spryk</>', $sprykName));
        $this->printTableWithRequiredArguments($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS], $output);
        $this->printTableWithOptionalArguments($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS], $output);
        $this->printTableWithPreSpryks($sprykDefinition['preSpryks'] ?? [], $output);
        $this->printTableWithPostSpryks($sprykDefinition['postSpryks'] ?? [], $output);
        $this->printCommandRunExample($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS], $sprykName, $output);
    }

    protected function printTableWithRequiredArguments(array $sprykArguments, OutputInterface $output): void
    {
        $output->writeln('');

        $headers = ['Required Argument', 'Description'];
        $rows = [];

        foreach ($sprykArguments as $sprykArgumentName => $sprykArgumentDefinition) {
            if (isset($sprykArgumentDefinition['value'])) {
                continue;
            }
            if (isset($sprykArgumentDefinition['default'])) {
                continue;
            }
            $rows[] = [$sprykArgumentName, $sprykArgumentDefinition['description'] ?? 'No description provided'];
        }

        if (count($rows)) {
            $output->writeln('<fg=yellow>The following arguments are required and you need to pass them</>');
            $this->printTable($output, $headers, $rows);

            return;
        }

        $output->writeln('<fg=yellow>This Spryk does not have any required arguments to be passed</>');
    }

    protected function printTableWithOptionalArguments(array $sprykArguments, OutputInterface $output): void
    {
        $headers = ['Optional Argument', 'Description', 'Default', 'Value'];
        $rows = [];

        foreach ($sprykArguments as $sprykArgumentName => $sprykArgumentDefinition) {
            if (!isset($sprykArgumentDefinition['value']) && !isset($sprykArgumentDefinition['default'])) {
                continue;
            }

            $value = '';

            if (isset($sprykArgumentDefinition['value'])) {
                $value = is_array($sprykArgumentDefinition['value']) ? 'Is an array check Spryk definition' : substr($sprykArgumentDefinition['value'], 0, 100);
            }

            $rows[] = [
                $sprykArgumentName,
                isset($sprykArgumentDefinition['description']) ? substr($sprykArgumentDefinition['description'], 0, 100) : '',
                $value,
                isset($sprykArgumentDefinition['default']) ? substr($sprykArgumentDefinition['default'], 0, 100) : '',
            ];
        }

        if (count($rows)) {
            $output->writeln('');
            $output->writeln('<fg=yellow>The following arguments are optional and you can pass them when needed</>');
            $this->printTable($output, $headers, $rows);
        }
    }

    protected function printTableWithPreSpryks(array $preSpryks, OutputInterface $output): void
    {
        $output->writeln('');

        $headers = ['PreSpryks'];
        $rows = [];

        foreach ($preSpryks as $preSpryk) {
            if (is_array($preSpryk)) {
                $preSpryk = array_key_first($preSpryk);
            }

            $rows[] = [
                $preSpryk,
            ];
        }
        if (count($rows)) {
            $output->writeln('<fg=yellow>Pre Spryks which are executed before the Spryk is running</>');
            $this->printTable($output, $headers, $rows);

            return;
        }

        $output->writeln('<fg=yellow>This Spryk does not have any preSpryk to be executed</>');
    }

    protected function printTableWithPostSpryks(array $postSpryks, OutputInterface $output): void
    {
        $output->writeln('');

        $headers = ['PostSpryks'];
        $rows = [];

        foreach ($postSpryks as $postSpryk) {
            if (is_array($postSpryk)) {
                $postSpryk = array_key_first($postSpryk);
            }

            $rows[] = [
                $postSpryk,
            ];
        }
        if (count($rows)) {
            $output->writeln('<fg=yellow>Post Spryks which are executed after the Spryk was running</>');
            $this->printTable($output, $headers, $rows);

            return;
        }

        $output->writeln('<fg=yellow>This Spryk does not have any postSpryk to be executed</>');
    }

    protected function printTable(OutputInterface $output, array $headers, array $rows): void
    {
        (new Table($output))
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }

    protected function printCommandRunExample(array $arguments, string $sprykName, OutputInterface $output): void
    {
        $consoleOutput = [
            'vendor/bin/spryk-run',
            $sprykName,
        ];

        foreach ($arguments as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['value'])) {
                continue;
            }
            if (isset($argumentDefinition['default'])) {
                continue;
            }
            $consoleOutput[] = '--' . $argumentName;
            $consoleOutput[] = $argumentDefinition['example'] ?? $argumentName . 'Value';
        }

        $consoleOutput[] = '--no-interaction';

        $output->writeln('');
        $output->writeln('Use the following command to run this Spyk. You need to replace the placeholder values with your real value.');
        $output->writeln('');
        $output->writeln(sprintf('<fg=green>%s</>', implode(' ', $consoleOutput)));
        $output->writeln('');
    }

    /**
     * @throws \Exception
     */
    protected function formatSpryks(array $sprykDefinitions): array
    {
        $formatted = [];
        foreach ($sprykDefinitions as $sprykName => $sprykDefinition) {
            if (!isset($sprykDefinition['description'])) {
                throw new Exception(sprintf('The Spryk "%s" doesn\'t have a description.', $sprykName));
            }
            $formatted[$sprykName] = [
                $sprykName,
                $sprykDefinition['description'],
                $this->formatArguments($sprykDefinition),
            ];
        }
        sort($formatted);

        return $formatted;
    }

    protected function formatArguments(array $sprykDefinition): string
    {
        return implode(', ', array_keys($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]));
    }
}
