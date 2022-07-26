<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder;

use InvalidArgumentException;
use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface;
use SprykerSdk\Spryk\SprykConfig;
use SprykerSdk\Spryk\Style\SprykStyleInterface;

abstract class AbstractBuilder implements SprykBuilderInterface
{
    /**
     * @var string
     */
    public const ARGUMENT_TARGET = 'target';

    /**
     * @var string
     */
    public const ARGUMENT_TARGET_PATH = 'targetPath';

    /**
     * @var string
     */
    public const ARGUMENT_ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const ARGUMENT_MODULE = 'module';

    /**
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected SprykConfig $config;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface
     */
    protected FileResolverInterface $fileResolver;

    /**
     * @var \SprykerSdk\Spryk\Style\SprykStyleInterface|null
     */
    protected ?SprykStyleInterface $style = null;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface
     */
    protected SprykDefinitionInterface $definition;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface
     */
    protected ArgumentCollectionInterface $arguments;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     */
    public function __construct(SprykConfig $config, FileResolverInterface $fileResolver)
    {
        $this->config = $config;
        $this->fileResolver = $fileResolver;
    }

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\SprykDefinitionInterface $definition
     * @param \SprykerSdk\Spryk\Style\SprykStyleInterface|null $style
     *
     * @return void
     */
    public function runSpryk(SprykDefinitionInterface $definition, ?SprykStyleInterface $style = null): void
    {
        $this->definition = $definition;
        $this->arguments = $definition->getArgumentCollection();
        $this->style = $style;

        if ($this->shouldBuild()) {
            $this->build();
        }
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    abstract protected function build(): void;

    /**
     * @retrun void
     *
     * @param string $message
     *
     * @return void
     */
    protected function log(string $message): void
    {
        if (!$this->style) {
            return;
        }

        $this->style->report($message);
    }

    /**
     * @return string
     */
    protected function getSprykName(): string
    {
        return $this->definition->getSprykName();
    }

    /**
     * @return string
     */
    protected function getOrganizationName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_ORGANIZATION);
    }

    /**
     * @return string
     */
    protected function getModuleName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_MODULE);
    }

    /**
     * @return string
     */
    protected function getTargetPath(): string
    {
        return $this->getAbsoluteTargetPath($this->getStringArgument(static::ARGUMENT_TARGET_PATH));
    }

    /**
     * @param string $relativeFilePath
     *
     * @return string
     */
    protected function getFileTargetPath(string $relativeFilePath): string
    {
        $relativeDirPath = dirname($relativeFilePath);
        $fileName = basename($relativeFilePath);

        return $this->getAbsoluteTargetPath($relativeDirPath) . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param string $relativeDirPath
     *
     * @return string
     */
    protected function getAbsoluteTargetPath(string $relativeDirPath): string
    {
        if ($this->arguments->hasArgument('mode') && $this->getStringArgument('mode') === 'project') {
            $path = rtrim($this->config->getProjectRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            return $path . $relativeDirPath;
        }

        if (!$this->arguments->hasArgument(static::ARGUMENT_MODULE) || $this->arguments->getArgument(static::ARGUMENT_MODULE)->getValue() === null) {
            return rtrim($this->config->getProjectRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $relativeDirPath;
        }

        $moduleName = $this->getModuleName();

        $targetPathFragments = [
            'vendor',
            'spryker',
            $this->getDasherizeFilter()->filter($this->getStringArgument(static::ARGUMENT_ORGANIZATION)),
            'Bundles',
            $moduleName,
            $relativeDirPath,
        ];

        return rtrim($this->config->getProjectRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $targetPathFragments);
    }

    /**
     * @return \Laminas\Filter\FilterChain
     */
    protected function getDasherizeFilter(): FilterChain
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain;
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return $this->getStringArgument(static::ARGUMENT_TARGET);
    }

    /**
     * @param string $argumentName
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getStringArgument(string $argumentName): string
    {
        $value = $this->arguments->getArgument($argumentName)->getValue();

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Argument value for "%s" should be a string, found "%s"', $argumentName, gettype($value)));
        }

        return $value;
    }

    /**
     * @param string $argumentName
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getArrayArgument(string $argumentName): array
    {
        $value = $this->arguments->getArgument($argumentName)->getValue();

        if (!is_array($value)) {
            throw new InvalidArgumentException(sprintf('Argument value for "%s" should be an array, found "%s"', $argumentName, gettype($value)));
        }

        return $value;
    }

    /**
     * @param string $argumentName
     *
     * @return mixed
     */
    protected function getArgumentByName(string $argumentName)
    {
        return $this->arguments->getArgument($argumentName)->getValue();
    }
}
