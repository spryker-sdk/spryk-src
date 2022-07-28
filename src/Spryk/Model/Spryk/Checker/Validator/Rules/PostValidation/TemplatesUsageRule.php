<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation;

use SprykerSdk\Spryk\Model\Spryk\Checker\Finder\SprykTemplateFinderInterface;
use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;
use SprykerSdk\Spryk\SprykConfig;

class TemplatesUsageRule implements PostValidationInterface
{

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\Finder\SprykTemplateFinderInterface
     */
    protected SprykTemplateFinderInterface $sprykTemplateFinder;

    /**
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected SprykConfig $config;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\Finder\SprykTemplateFinderInterface $sprykTemplateFinder
     * @param \SprykerSdk\Spryk\SprykConfig $config
     */
    public function __construct(SprykTemplateFinderInterface $sprykTemplateFinder, SprykConfig $config)
    {
        $this->sprykTemplateFinder = $sprykTemplateFinder;
        $this->config = $config;
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
        };

        return $sprykDetails;
    }


    /**
     * @param array $sprykDetails
     *
     * @return void
     */
    public function fix(array $sprykDetails)
    {
        $usedTemplates = $this->extractUsedTemplates($sprykDetails);

        foreach ($this->collectUnusedTemplates($usedTemplates) as $unusedTemplate) {
            unlink($unusedTemplate->getRealPath());
        }
    }

    /**
     * Returns a list of Spryk properties where we will look for template usage.
     *
     * @return string[]
     */
    protected function getPropertiesWithSpryks(): array
    {
        return [
            'spryks',
            'preSpryks',
            'postSpryks',
        ];
    }
    /**
     * @param array $sprykDetails
     *
     * @return array
     */
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
                    if (preg_match_all(
                        '/\'((\S*)\.twig)\'/',
                        file_get_contents($directory . $template),
                        $outputArray
                    )) {
                        foreach ($outputArray[1] as $foundTemplate) {
                            $usedTemplates[] = $foundTemplate;
                        }
                    }
                } catch (\Throwable $exception) {}
            }
        }

        return $usedTemplates;
    }

    /**
     * @param array $usedTemplates
     * @param array $spryk
     *
     * @return void
     */
    protected function checkTemplateArgumentExisting(array &$usedTemplates, array $spryk)
    {
        foreach ($this->getArgumentListWhichTemplateCanBeUsed() as $argumentName) {
            if (isset($spryk['arguments'][$argumentName])) {
                $argument = $spryk['arguments'][$argumentName];
                $this->checkTemplateInArgument($usedTemplates, $argument);
            }
        }
    }

    /**
     * @return string[]
     */
    protected function getArgumentListWhichTemplateCanBeUsed(): array
    {
        return [
            'template',
            'body',
        ];
    }

    /**
     * @param array $usedTemplates
     * @param $argument
     *
     * @return void
     */
    protected function checkTemplateInArgument(array &$usedTemplates, $argument)
    {
        if (is_array($argument)) {
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
        }

        if (isset($argument['value'])) {
            $this->writeUnusedTemplate($usedTemplates, $argument['value']);

        }
    }

    /**
     * @param $usedTemplates
     * @param $argument
     *
     * @return void
     */
    protected function writeUnusedTemplate(&$usedTemplates, $argument)
    {
        if (!$this->isTwigTemplateExtension($argument)) {
            return;
        }

        $usedTemplates[] = $argument;
    }

    /**
     * @param $argumentValue
     *
     * @return bool
     */
    protected function isTwigTemplateExtension($argumentValue): bool
    {
        return strpos($argumentValue, '.twig') !== false;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function getFileName(string $template): string
    {
        return array_reverse(explode("/", $template))[0];
    }

    /**
     * @param array $usedTemplates
     *
     * @return array
     */
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

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getTemplatePath(string $templateName): string
    {
        return '';
    }

    /**
     * @param string $templatePath
     *
     * @return string
     */
    protected function prepareWarningMessage(string $templatePath): string
    {
        return sprintf(
            'Found unused template: %s',
            $templatePath,
        );
    }
}
