<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation;

use SprykerSdk\Spryk\Model\Spryk\Checker\Finder\SprykTemplateFinderInterface;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;
use SprykerSdk\Spryk\SprykConfig;
use Throwable;

class TemplatesUsageRule implements PostValidationInterface
{
    public function __construct(protected SprykTemplateFinderInterface $sprykTemplateFinder, protected SprykConfig $config)
    {
    }

    /**
     * @param array $sprykDetails
     *
     * @return array
     */
    public function validate(array $sprykDetails): array
    {
        $usedTemplates = $this->extractUsedTemplates($sprykDetails);

        foreach ($this->collectUnusedTemplates($usedTemplates) as $unusedTemplate) {
            $sprykDetails[CheckerValidatorRuleInterface::HAVE_WARNINGS] = true;
            $sprykDetails[CheckerValidatorRuleInterface::GENERAL_WARNINGS][]
                = $this->prepareWarningMessage($unusedTemplate->getRealPath());
        }

        return $sprykDetails;
    }

    public function fix(array $sprykDetails): void
    {
        $usedTemplates = $this->extractUsedTemplates($sprykDetails);

        foreach ($this->collectUnusedTemplates($usedTemplates) as $unusedTemplate) {
            unlink($unusedTemplate->getRealPath());
        }
    }

    /**
     * Returns a list of Spryk properties where we will look for template usage.
     *
     * @return array<string>
     */
    protected function getPropertiesWithSpryks(): array
    {
        return [
            'spryks',
            'preSpryks',
            'postSpryks',
        ];
    }

    protected function extractUsedTemplates(array $sprykDetails): array
    {
        $usedTemplates = [];

        // Looking for templates in the Spryks
        foreach ($sprykDetails['definitions'] as $sprykDefinition) {
            $sprykDefinition = $sprykDefinition['definition'];
            $this->checkTemplateArgumentExisting($usedTemplates, $sprykDefinition);

            foreach ($this->getPropertiesWithSpryks() as $propertyName) {
                if (isset($sprykDefinition[$propertyName])) {
                    foreach ($sprykDefinition[$propertyName] as $preSpryk) {
                        if (is_string($preSpryk)) {
                            continue;
                        }
                        $this->checkTemplateArgumentExisting($usedTemplates, array_values($preSpryk)[0]);
                    }
                }
            }
        }

        // Looking for templates in templates found earlier
        $usedTemplates = array_unique($usedTemplates);

        foreach ($usedTemplates as $template) {
            foreach ($this->config->getTemplateDirectories() as $directory) {
                try {
                    $fileContent = file_get_contents($directory . $template);
                    if (!$fileContent) {
                        continue;
                    }

                    if (preg_match_all('/\'((\S*)\.twig)\'/', $fileContent, $outputArray)) {
                        foreach ($outputArray[1] as $foundTemplate) {
                            $usedTemplates[] = $foundTemplate;
                        }
                    }
                } catch (Throwable) {
                }
            }
        }

        return $usedTemplates;
    }

    protected function checkTemplateArgumentExisting(array &$usedTemplates, array $spryk): void
    {
        foreach ($this->getArgumentListWhichTemplateCanBeUsed() as $argumentName) {
            if (isset($spryk['arguments'][$argumentName])) {
                $argument = $spryk['arguments'][$argumentName];
                $this->checkTemplateInArgument($usedTemplates, $argument);
            }
        }
    }

    /**
     * @return array<string>
     */
    protected function getArgumentListWhichTemplateCanBeUsed(): array
    {
        return [
            'template',
            'body',
        ];
    }

    protected function checkTemplateInArgument(array &$usedTemplates, array $argument): void
    {
        if (isset($argument['value'])) {
            if (is_array($argument['value'])) {
                return;
            }
            $this->writeUnusedTemplate($usedTemplates, $argument['value']);
        }
        if (isset($argument['default'])) {
            if (is_array($argument['default'])) {
                return;
            }
            $this->writeUnusedTemplate($usedTemplates, $argument['default']);
        }
        if (isset($argument['value'])) {
            $this->writeUnusedTemplate($usedTemplates, $argument['value']);
        }
    }

    protected function writeUnusedTemplate(array &$usedTemplates, string $argument): void
    {
        if (!$this->isTwigTemplateExtension($argument)) {
            return;
        }

        $usedTemplates[] = $argument;
    }

    protected function isTwigTemplateExtension(string $argumentValue): bool
    {
        return str_contains($argumentValue, '.twig');
    }

    protected function getFileName(string $template): string
    {
        return array_reverse(explode('/', $template))[0];
    }

    protected function collectUnusedTemplates(array $usedTemplates): array
    {
        $unusedTemplates = [];

        foreach ($this->sprykTemplateFinder->find() as $template) {
            if (!in_array($template->getRelativePathname(), $usedTemplates)) {
                $unusedTemplates[] = $template;
            }
        }

        return array_unique($unusedTemplates);
    }

    protected function getTemplatePath(string $templateName): string
    {
        return '';
    }

    protected function prepareWarningMessage(string $templatePath): string
    {
        return sprintf(
            'Found unused template: %s',
            $templatePath,
        );
    }
}
