<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckSprykDefinition extends AbstractSprykConsole
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'spryk:check-definition';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Runs a Spryk definition check process.';

    /**
     * @var string
     */
    public const ARGUMENT_SPRYK = 'spryk';

    /**
     * @var string
     */
    public const OPTION_FIX = 'fix';
    /**
     * @var string
     */
    public const OPTION_FIX_SHORT = 'f';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(
                static::ARGUMENT_SPRYK,
                InputOption::VALUE_OPTIONAL,
                'Name of a specific Spryk for which the options should be dumped.',
            )
            ->addOption(
                static::OPTION_FIX,
                static::OPTION_FIX_SHORT,
                InputOption::VALUE_OPTIONAL,
                'Spryk fix mode.',
                false
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
        $isFix = is_null($input->getOption(static::OPTION_FIX));
        $sprykName = $this->extractSprykName($input);

        try {
            if ($isFix) {
                $validationResult = $this->getFacade()->fixSprykDefinitions($sprykName);
            } else {
                $validationResult = $this->getFacade()->checkSprykDefinitions($sprykName);
            }

            if (isset($validationResult['have_errors'])) {
                $this->printSprykDefinitionsErrors($output, $validationResult);
                return static::ERROR_CODE;
            }
        } catch (\Throwable $exception) {
            $this->printErrorMessage($output, $exception->getMessage());

            return static::ERROR_CODE;
        }

        $this->printSuccessfulMessage($output);
        return static::SUCCESS_CODE;
    }

    protected function printSprykDefinitionsErrors(OutputInterface $output, array $validationResult)
    {
        foreach ($this->prepareSprykDefinitionsErrors($validationResult) as $rulesErrors) {
            foreach ($rulesErrors as $ruleErrors) {
                foreach ($ruleErrors as $errorMessage) {
                    $this->printErrorMessage($output, $errorMessage);
                }
            }
        }
    }

    protected function prepareSprykDefinitionsErrors(array $validationResult): array
    {
        $errors = [];
        foreach ($validationResult['definitions'] as $sprykName => $checkedSprykDefinition) {
            foreach ($checkedSprykDefinition['errors'] as $rule => $errorRule) {
                foreach ($errorRule->getErrorMessages() as $errorMessage) {
                    $errors[$sprykName][$rule][] = $errorMessage;
                }
            }
        }

        return $errors;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return false|mixed
     */
    protected function extractSprykName(InputInterface $input)
    {
        return current((array)$input->getArgument(static::ARGUMENT_SPRYK)) ?? null;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $message
     *
     * @return void
     */
    protected function printErrorMessage(OutputInterface $output, string $message): void
    {
        $output->writeln('<error>' . $message . '</error>');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function printSuccessfulMessage(OutputInterface $output)
    {
        $output->writeln('<info>No validation errors found</info>');
    }
}
