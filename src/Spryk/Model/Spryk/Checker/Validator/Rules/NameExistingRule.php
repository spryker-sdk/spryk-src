<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
     * @param array $spryk
     *
     * @return bool
     */
    protected function isSprykNameEqualsFileName(array $spryk): bool
    {
        $fileName = $this->extractFileNameByPath($spryk['path']);

        return isset($spryk['definition']['name']) && $fileName === $spryk['definition']['name'];
    }

    /**
     * @param string $filePath
     *
     * @return string|false
     */
    protected function extractFileNameByPath(string $filePath)
    {
        $filePath = explode('/', $filePath);
        $dotPosition = strpos(end($filePath), '.');

        return substr(end($filePath), 0, $dotPosition !== false ? $dotPosition : null);
    }

    /**
     * @param array $checkedSpryk
     *
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
            Yaml::DUMP_NULL_AS_TILDE,
        );

        file_put_contents($sprykPath, $normalizedYml);
    }
}
