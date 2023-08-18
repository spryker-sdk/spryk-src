<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Glue;

use SprykerSdk\Spryk\Exception\SprykException;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface;

class UpdateGlueValidationSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    protected const IS_OPTIONAL = 'Optional';

    /**
     * @var string
     */
    protected const IS_REQUIRED = 'Required';

    /**
     * @var string
     */
    protected const ARGUMENT_RESOURCE = 'resource';

    /**
     * @var string
     */
    protected const ARGUMENT_HTTP_METHOD = 'httpMethod';

    /**
     * @var string
     */
    protected const ARGUMENT_FIELD = 'field';

    /**
     * @var string
     */
    protected const ARGUMENT_IS_REQUIRED = 'isRequired';

    /**
     * @var string
     */
    protected const ARGUMENT_TARGET_FILE = 'targetFile';

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        $targetFile = $this->getTargetFile();
        $resolvedTargetFile = $this->fileResolver->resolve($targetFile);

        if (!($resolvedTargetFile instanceof ResolvedYmlInterface)) {
            return true;
        }

        $validationStructure = $resolvedTargetFile->getDecodedYml();

        return !isset($validationStructure[$this->getResource()][$this->getMethod()][$this->getField()]);
    }

    /**
     * @return void
     */
    protected function build(): void
    {
        $targetFile = $this->getTargetFile();
        $resource = $this->getResource();
        $method = $this->getMethod();
        $field = $this->getField();

        $resolvedTargetFile = $this->getResolvedYmlFile($targetFile);

        $validationStructure = $resolvedTargetFile->getDecodedYml();

        $validationStructure = $this->populateValidationStructure($validationStructure, $resource, $method, $field);

        $this->createTargetFileDirIfNotExists($targetFile);

        $resolvedTargetFile->setDecodedYml($validationStructure);

        $this->log(sprintf('New Glue validation rule [%s]%s:%s has been added to %s', $method, $resource, $field, $targetFile));
    }

    /**
     * @throws \SprykerSdk\Spryk\Exception\SprykException
     */
    protected function getResolvedYmlFile(string $targetFile): ResolvedYmlInterface
    {
        $resolvedTargetFile = $this->fileResolver->resolve($targetFile);

        if (!($resolvedTargetFile instanceof ResolvedYmlInterface)) {
            throw new SprykException(sprintf('Unable to resolve file %s', $targetFile));
        }

        return $resolvedTargetFile;
    }

    protected function populateValidationStructure(array $validationStructure, string $resource, string $method, string $field): array
    {
        if (!isset($validationStructure[$resource])) {
            $validationStructure[$resource] = [];
        }

        if (!isset($validationStructure[$resource][$method])) {
            $validationStructure[$resource][$method] = [];
        }

        $validationStructure[$resource][$method][$field] = $this->getIsRequired() ? [static::IS_REQUIRED] : [static::IS_OPTIONAL];

        return $validationStructure;
    }

    protected function createTargetFileDirIfNotExists(string $targetFile): void
    {
        $targetDir = dirname($targetFile);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'updateGlueValidation';
    }

    protected function getResource(): string
    {
        return (string)$this->arguments->getArgument(static::ARGUMENT_RESOURCE)->getValue();
    }

    protected function getMethod(): string
    {
        return strtolower($this->arguments->getArgument(static::ARGUMENT_HTTP_METHOD)->getValue());
    }

    protected function getField(): string
    {
        return (string)$this->arguments->getArgument(static::ARGUMENT_FIELD)->getValue();
    }

    protected function getIsRequired(): bool
    {
        $isRequired = $this->arguments->getArgument(static::ARGUMENT_IS_REQUIRED)->getValue();

        return $isRequired === 'false' ? false : (bool)$isRequired;
    }

    protected function getTargetFile(): string
    {
        return $this->getFileTargetPath((string)$this->arguments->getArgument(static::ARGUMENT_TARGET_FILE)->getValue());
    }
}
