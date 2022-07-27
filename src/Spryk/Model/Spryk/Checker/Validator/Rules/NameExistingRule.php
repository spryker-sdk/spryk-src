<?php

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

use Symfony\Component\Yaml\Yaml;

class NameExistingRule extends AbstractCheckerValidatorRule
{

    /**
     * @param array $spryk
     *
     * @return void
     */
    protected function innerValidate(array $spryk): void
    {
        if (!$this->isNamePropertyExists($spryk['definition'])) {
            $this->addErrorMessage('The name does not exist in the Spryk.');
        }

        if (!$this->isSprykNameEqualsFileName($spryk)) {
            $this->addErrorMessage('Spryk name does not equal file name.');
        }
    }

    /**
     * @param array $sprykDefinition
     *
     * @return bool
     */
    protected function isNamePropertyExists(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['name']);
    }

    /**
     * @return void
     */
    protected function isSprykNameEqualsFileName(array $spryk): bool
    {
        $fileName = $this->extractFileNameByPath($spryk['path']);

        return isset($spryk['definition']['name']) && $fileName === $spryk['definition']['name'];
    }

    protected function extractFileNameByPath(string $filePath)
    {
        $filePath = explode('/', $filePath);
        return substr(end($filePath), 0, strpos(end($filePath), "."));
    }

    /**
     * @param array $checkedSpryk
     * @return void
     */
    public function fixPossibleIssue(array $checkedSpryk): void
    {
        $sprykPath = $checkedSpryk['path'];
        $spryk = Yaml::parseFile($sprykPath);

        $normalizedYml = Yaml::dump(
            ['name' => $this->extractFileNameByPath($sprykPath)] + $spryk,
            1000000,
            4,
            Yaml::DUMP_NULL_AS_TILDE
        );

        file_put_contents($sprykPath, $normalizedYml);
    }
}
