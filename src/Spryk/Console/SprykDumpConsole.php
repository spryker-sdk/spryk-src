<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use Exception;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\Console\Helper\FormatterHelper;
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

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return int|null
     */
    protected function getLevelOption(InputInterface $input): ?int
    {
        $level = current((array)$input->getOption(static::OPTION_LEVEL));

        return $level === 'all' ? null : (int)$level;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param int|null $level
     *
     * @return void
     */
    protected function dumpAllSpryks(OutputInterface $output, ?int $level): void
    {
        $sprykDefinitions = $this->getFacade()->getSprykDefinitions($level);
        $sprykDefinitions = $this->formatSpryks($sprykDefinitions);

        $output->writeln('List of Spryk definitions:');
        $this->printTable($output, ['Spryk name', 'Description', 'Arguments'], $sprykDefinitions);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $sprykName
     *
     * @return void
     */
    protected function dumpSpryk(OutputInterface $output, string $sprykName): void
    {
        $sprykDefinition = $this->getFacade()->getSprykDefinition($sprykName);
        $sprykArguments = $this->formatSingleSprykArguments($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]);
        $sprykDescription = $this->formatSingleSprykDescription($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_DESCRIPTION]);

        $this->printTable($output, [$sprykName, $sprykDescription], $sprykArguments);

        $optionalSpryks = $this->getFormattedOptionalSpryks($sprykDefinition);
        if ($optionalSpryks !== []) {
            $this->printTitleBlock($output, 'Optional Spryks:');
            $this->printTable($output, ['Spryk'], $optionalSpryks);
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $title
     * @param string $style
     *
     * @return void
     */
    protected function printTitleBlock(OutputInterface $output, string $title, string $style = 'info'): void
    {
        $output->writeln(
            (new FormatterHelper())
                ->formatBlock($title, $style),
        );
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    protected function printTable(OutputInterface $output, array $headers, array $rows): void
    {
        (new Table($output))
            ->setStyle('compact')
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }

    /**
     * @param array $sprykDefinitions
     *
     * @throws \Exception
     *
     * @return array
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

    /**
     * @param array $sprykDefinition
     *
     * @return array
     */
    protected function formatSingleSprykArguments(array $sprykDefinition): array
    {
        $formatted = ['mode' => ['mode']];
        foreach ($sprykDefinition as $argument => $argumentDefinition) {
            if (isset($argumentDefinition[SprykConfig::NAME_ARGUMENT_KEY_VALUE])) {
                continue;
            }

            $formatted[$argument] = [
                $argument,
                $argumentDefinition[SprykConfig::NAME_ARGUMENT_KEY_DESCRIPTION] ?? '',
            ];
        }
        sort($formatted);

        return $formatted;
    }

    /**
     * @param string $sprykDescription
     *
     * @return string
     */
    protected function formatSingleSprykDescription(string $sprykDescription): string
    {
        return trim($sprykDescription);
    }

    /**
     * @param array $sprykDefinition
     *
     * @return string
     */
    protected function formatArguments(array $sprykDefinition): string
    {
        return implode(', ', array_keys($sprykDefinition[SprykConfig::SPRYK_DEFINITION_KEY_ARGUMENTS]));
    }

    /**
     * @param array $sprykDefinition
     *
     * @return array
     */
    protected function getFormattedOptionalSpryks(array $sprykDefinition): array
    {
        $optionalSpryks = [];
        $preAndPostSpryks = $this->getPreAndPostSpryks($sprykDefinition);
        $preAndPostSpryks = $this->filterSprykDefinitions($preAndPostSpryks);

        $flattenPreAndPostSpryks = array_reduce($preAndPostSpryks, 'array_merge', []);
        foreach ($flattenPreAndPostSpryks as $sprykName => $flattenPreAndPostSpryk) {
            if (!$this->isOptionalSpryk($flattenPreAndPostSpryk)) {
                continue;
            }

            $optionalSpryks[$sprykName] = [$sprykName];
        }

        return $optionalSpryks;
    }

    /**
     * @param array $sprykDefinition
     *
     * @return array
     */
    protected function getPreAndPostSpryks(array $sprykDefinition): array
    {
        return ($sprykDefinition['preSpryks'] ?? []) + ($sprykDefinition['postSpryks'] ?? []);
    }

    /**
     * @param array $sprykDefinitions
     *
     * @return array
     */
    protected function filterSprykDefinitions(array $sprykDefinitions): array
    {
        return array_filter($sprykDefinitions, 'is_array');
    }

    /**
     * @param array $sprykDefinition
     *
     * @return bool
     */
    protected function isOptionalSpryk(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['isOptional']) && $sprykDefinition['isOptional'] === true;
    }
}
