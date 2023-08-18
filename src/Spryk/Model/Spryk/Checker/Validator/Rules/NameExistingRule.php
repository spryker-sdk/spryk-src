<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Checker\Validator\Rules;

class NameExistingRule extends AbstractCheckerValidatorRule
{
    /**
     * @var string
     */
    protected const NAME_PARAMETER_REGEX = "/name: \w+\n/";

    /**
     * @var string
     */
    protected const NAME_PARAMETER_TEMPLATE = "name: %s\n";

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

    protected function isNamePropertyExists(array $sprykDefinition): bool
    {
        return isset($sprykDefinition['name']);
    }

    protected function isSprykNameEqualsFileName(array $spryk): bool
    {
        $fileName = $this->extractFileNameByPath($spryk['path']);

        return isset($spryk['definition']['name']) && $fileName === $spryk['definition']['name'];
    }

    protected function extractFileNameByPath(string $filePath): ?string
    {
        $filePath = explode('/', $filePath);
        $dotPosition = strpos(end($filePath), '.');
        $fileName = $dotPosition ? substr(end($filePath), 0, $dotPosition) : null;

        return $fileName ?: null;
    }

    /**
     * @param array $checkedSpryk
     *
     * @return void
     */
    public function fixPossibleIssue(array $checkedSpryk): void
    {
        $sprykPath = $checkedSpryk['path'];
        $sprykContents = file_get_contents($sprykPath);

        if (!$sprykContents) {
            return;
        }

        $nameParameterLine = $this->getNameParameterLine($sprykContents);
        $validNameParameterLine = sprintf(static::NAME_PARAMETER_TEMPLATE, $this->extractFileNameByPath($sprykPath));

        $isContentModified = false;

        if (!$nameParameterLine) {
            $sprykContents = $validNameParameterLine . $sprykContents;
            $isContentModified = true;
        }

        if ($nameParameterLine !== $validNameParameterLine) {
            $sprykContents = str_replace($nameParameterLine, $validNameParameterLine, $sprykContents);
            $isContentModified = true;
        }

        if ($isContentModified) {
            file_put_contents($sprykPath, $sprykContents);
        }
    }

    protected function getNameParameterLine(string $sprykContents): string
    {
        $matches = [];
        preg_match(static::NAME_PARAMETER_REGEX, $sprykContents, $matches);

        return count($matches) ? $matches[0] : '';
    }
}
