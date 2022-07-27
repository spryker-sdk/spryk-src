<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

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
                false,
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
        $isFix = $input->getOption(static::OPTION_FIX) === null;

        try {
            if ($isFix) {
                $validationResult = $this->getFacade()->fixSprykDefinitions();
            } else {
                $validationResult = $this->getFacade()->checkSprykDefinitions();
            }

            if (isset($validationResult['have_errors']) || isset($validationResult['have_warnings'])) {
                $this->printSprykDefinitionsErrorsAndWarnings($output, $validationResult);

                return isset($validationResult['have_errors']) ? static::ERROR_CODE : static::WARNING_CODE;
            }
        } catch (Throwable $exception) {
            $this->printErrorMessage($output, $exception->getMessage());

            return static::ERROR_CODE;
        }

        $this->printSuccessfulMessage($output);

        return static::SUCCESS_CODE;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $validationResult
     *
     * @return void
     */
    protected function printSprykDefinitionsErrorsAndWarnings(OutputInterface $output, array $validationResult): void
    {
        [$errors, $warnings] = $this->prepareSprykDefinitionsErrors($validationResult);

        foreach (array_keys($validationResult['definitions']) as $sprykName) {
            if (isset($errors[$sprykName])) {
                $this->printInfoMessage($output, sprintf('List of the %s Spryk errors.', $sprykName));
                foreach ($errors[$sprykName] as $rule) {
                    foreach ($rule->getErrorMessages() as $error) {
                        $this->printErrorMessage($output, $error);
                    }
                }

                if (isset($warnings[$sprykName])) {
                    $this->printInfoMessage($output, sprintf('List of the %s Spryk warnings.', $sprykName));
                    foreach ($warnings[$sprykName] as $warning) {
                        $this->printWarningMessage($output, $warning);
                    }
                }
            }
        }
    }

    /**
     * @param array $validationResult
     *
     * @return array
     */
    protected function prepareSprykDefinitionsErrors(array $validationResult): array
    {
        $errors = [];
        $warnings = [];

        foreach ($validationResult['definitions'] as $sprykName => $checkedSprykDefinition) {
            foreach ($checkedSprykDefinition[CheckerValidatorRuleInterface::ERRORS_KEY] as $ruleKey => $rule) {
                    $errors[$sprykName][$ruleKey] = $rule;
            }

            if (!isset($checkedSprykDefinition[CheckerValidatorRuleInterface::WARNINGS_RULE_KEY])) {
                continue;
            }

            foreach ($checkedSprykDefinition[CheckerValidatorRuleInterface::WARNINGS_RULE_KEY] as $warningMessage) {
                $warnings[$sprykName][] = $warningMessage;
            }
        }

        return [$errors, $warnings];
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return mixed|false
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
     * @param string $message
     *
     * @return void
     */
    protected function printWarningMessage(OutputInterface $output, string $message): void
    {
        $output->writeln('<bg=yellow>' . $message . '</>');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $message
     *
     * @return void
     */
    protected function printInfoMessage(OutputInterface $output, string $message): void
    {
        $output->writeln('<comment>' . $message . '</comment>');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printSuccessfulMessage(OutputInterface $output): void
    {
        $output->writeln('<info>No validation errors found</info>');
    }
}
