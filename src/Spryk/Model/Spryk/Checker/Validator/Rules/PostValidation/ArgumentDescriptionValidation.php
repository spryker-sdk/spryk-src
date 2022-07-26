<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\PostValidation;

use SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules\CheckerValidatorRuleInterface;

class ArgumentDescriptionValidation implements PostValidationInterface
{
    /**
     * @param array $sprykDefinitions
     * @return array
     */
    public function validate(array $sprykDefinitions): array
    {
        $argumentDescriptionCompareBuffer = [];

        foreach ($this->extractArgumentDescription($sprykDefinitions) as $sprykName => $sprykArgumentsDescriptions) {
            foreach ($sprykArgumentsDescriptions as $argumentName => $argumentsDescription) {
                if (!isset($argumentDescriptionCompareBuffer[$argumentName])) {
                    $argumentDescriptionCompareBuffer[$argumentName] = $argumentsDescription;
                    continue;
                }

                if ($argumentDescriptionCompareBuffer[$argumentName] !== $argumentsDescription) {
                    $sprykDefinitions['definitions'][$sprykName][CheckerValidatorRuleInterface::WARNINGS_RULE_KEY][]
                        = $this->getWarningForArgument($sprykName, $argumentName);
                }
            }
        }

        return $sprykDefinitions;
    }

    /**
     * @param array $sprykDefinitions
     * @return array
     */
    protected function extractArgumentDescription(array $sprykDefinitions): array
    {
        $argumentsDescriptions = [];
$arDebug = [
    'DATE' => '2022-07-26, вт, 9:14',
    'PATH' => 'src/Spryk/Model/Spryk/Checker/Validator/Rules/PostValidation/ArgumentDescriptionValidation.php:43',
    'DATA' => $sprykDefinitions['definitions'],

];
$debugInfo = "\n" . print_r($arDebug['DATE'], true) .  "\n" . print_r($arDebug['PATH'], true) . "\n" . print_r($arDebug['DATA'], true) . "\n";
    file_put_contents('/home/alexander/Projects/spryker/spryk-src/..log__definitions.txt', $debugInfo, FILE_APPEND);
        foreach ($sprykDefinitions['definitions'] as $sprykName => $sprykDetails) {
            foreach ($sprykDetails['definition']['arguments'] as $argumentName => $argumentDefinition) {
                if (!isset($argumentDefinition['description'])) {
                    continue;
                }
                $argumentsDescriptions[$sprykName][$argumentName] = $argumentDefinition['description'];
            }
        }

        return $argumentsDescriptions;
    }

    /**
     * @param string $sprykName
     * @param string $argumentName
     * @return string
     */
    protected function getWarningForArgument(string $sprykName, string $argumentName):  string
    {
        return sprintf(
            'The argument %s in the Spryk %s has a different value as found in other Spryks.',
            $argumentName,
            $sprykName
        );
    }
}
