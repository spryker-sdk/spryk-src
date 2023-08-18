<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Json;

use SprykerSdk\Spryk\Exception\YmlException;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UpdateJsonSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const ARGUMENT_TARGET_FILE_NAME = 'targetFilename';

    /**
     * @var string
     */
    protected const ARGUMENT_KEY = 'key';

    /**
     * @var string
     */
    protected const ARGUMENT_VALUE = 'value';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'update-json';
    }

    /**
     * @throws \SprykerSdk\Spryk\Exception\YmlException
     *
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedJsonInterface|null $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());

        if (!$resolved || empty($resolved->getDecodedJson())) {
            throw new YmlException(sprintf('The JSON file "%s" is empty or it was not able to parse it.', $this->getTargetPath()));
        }

        $resolved->setDecodedJson($this->updateJson($resolved->getDecodedJson()));

        $this->log(sprintf('Updated <fg=green>%s</>', $this->getTargetPath()));
    }

    /**
     * @return string
     */
    protected function getTargetPath(): string
    {
        $targetPath = parent::getTargetPath();

        $targetPath = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $fileName = $this->getStringArgument(static::ARGUMENT_TARGET_FILE_NAME);

        return $targetPath . $fileName;
    }

    protected function updateJson(array $jsonAsArray): array
    {
        $target = $this->getTarget();

        $key = $this->getKey();
        $value = $this->getValue();

        $targetElements = explode('.', $target);
        $target = sprintf('[%s]', implode('][', $targetElements));

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();

        $currentData = $propertyAccessor->getValue($jsonAsArray, $target);

        if ($currentData) {
            if ($key) {
                $currentData[$key] = $value;
                $propertyAccessor->setValue($jsonAsArray, $target, $currentData);

                return $jsonAsArray;
            }

            $currentData[] = $value;
            $propertyAccessor->setValue($jsonAsArray, $target, $currentData);

            return $jsonAsArray;
        }

        return $jsonAsArray;
    }

    protected function getKey(): string
    {
        return $this->getStringArgument(static::ARGUMENT_KEY);
    }

    protected function getValue(): string
    {
        return $this->getStringArgument(static::ARGUMENT_VALUE);
    }
}
