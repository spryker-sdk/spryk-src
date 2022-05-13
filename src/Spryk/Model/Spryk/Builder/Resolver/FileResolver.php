<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Resolver;

use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Exception\FileDoesNotContainClassOrInterfaceException;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface;

class FileResolver implements FileResolverInterface
{
    /**
     * @var array<string, \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface>
     */
    protected static $resolved = [];

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface
     */
    protected ParserInterface $classParser;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface
     */
    protected ParserInterface $ymlParser;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface
     */
    protected ParserInterface $jsonParser;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface
     */
    protected ParserInterface $xmlParser;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface
     */
    protected ParserInterface $fileParser;

    /**
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface $classParser
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface $ymlParser
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface $jsonParser
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface $xmlParser
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface $fileParser
     */
    public function __construct(
        ParserInterface $classParser,
        ParserInterface $ymlParser,
        ParserInterface $jsonParser,
        ParserInterface $xmlParser,
        ParserInterface $fileParser
    ) {
        $this->classParser = $classParser;
        $this->ymlParser = $ymlParser;
        $this->jsonParser = $jsonParser;
        $this->xmlParser = $xmlParser;
        $this->fileParser = $fileParser;
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface|null
     */
    public function resolve(string $name): ?ResolvedInterface
    {
        if (isset(static::$resolved[$name])) {
            return static::$resolved[$name];
        }

        $resolved = $this->searchInResolved($name);

        if ($resolved !== null) {
            return $resolved;
        }

        if (file_exists($name)) {
            return static::$resolved[$name] = $this->parseFile($name);
        }

        if (class_exists($name) || interface_exists($name)) {
            return static::$resolved[$name] = $this->classParser->parse($name);
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasResolved(string $name): bool
    {
        if (isset(static::$resolved[$name])) {
            return true;
        }

        return $this->searchInResolved($name) !== null;
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface|null
     */
    protected function searchInResolved(string $name): ?ResolvedInterface
    {
        foreach ($this->all() as $resolved) {
            if (
                $resolved instanceof ResolvedClassInterface
                && ($resolved->getClassName() === $name
                    || $resolved->getFullyQualifiedClassName() === $name
                    || $resolved->getFilePath() === $name)
            ) {
                return $resolved;
            }
        }

        return null;
    }

    /**
     * @param string $fileName
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface
     */
    protected function parseFile(string $fileName): ResolvedInterface
    {
        $extension = $this->getExtension($fileName);

        switch ($extension) {
            case 'php':
                try {
                    return $this->classParser->parse($fileName);
                } catch (FileDoesNotContainClassOrInterfaceException $e) {
                    return $this->fileParser->parse($fileName);
                }
            case 'yml':
            case 'yaml':
                return $this->ymlParser->parse($fileName);
            case 'json':
                return $this->jsonParser->parse($fileName);
            case 'xml':
                return $this->xmlParser->parse($fileName);
            default:
                return $this->fileParser->parse($fileName);
        }
    }

    /**
     * @param string $fileName
     *
     * @return string|null
     */
    protected function getExtension(string $fileName): ?string
    {
        $pathInfo = pathinfo($fileName);

        if (isset($pathInfo['extension'])) {
            return $pathInfo['extension'];
        }

        return null;
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    public function addFile(string $filePath, string $content): void
    {
        $extension = $this->getExtension($filePath);

        switch ($extension) {
            case 'php':
                $this->addPhpFile($filePath, $content);

                break;
            case 'yml':
            case 'yaml':
                $this->addYmlFile($filePath, $content);

                break;
            case 'json':
                $this->addJsonFile($filePath, $content);

                break;
            case 'xml':
                $this->addXmlFile($filePath, $content);

                break;
            default:
                $this->addDefaultFile($filePath, $content);
        }
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    protected function addPhpFile(string $filePath, string $content): void
    {
        try {
            /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
            $resolved = $this->classParser->parse($content);
        } catch (FileDoesNotContainClassOrInterfaceException $e) {
            $this->addDefaultFile($filePath, $content);

            return;
        }

        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$resolved->getClassName()] = $resolved;
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    protected function addYmlFile(string $filePath, string $content): void
    {
        $resolved = $this->ymlParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    protected function addJsonFile(string $filePath, string $content): void
    {
        $resolved = $this->jsonParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    protected function addXmlFile(string $filePath, string $content): void
    {
        $resolved = $this->xmlParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    protected function addDefaultFile(string $filePath, string $content): void
    {
        $resolved = $this->fileParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    /**
     * @return iterable<string, \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface>
     */
    public function all(): iterable
    {
        foreach (static::$resolved as $name => $resolved) {
            yield $name => $resolved;
        }
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        static::$resolved = [];
    }

    /**
     * @param string $type The interface name.
     *
     * @return array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface>
     */
    public function getResolvedByType(string $type): array
    {
        return array_filter(static::$resolved, function (ResolvedInterface $resolved) use ($type) {
            return $resolved instanceof $type;
        });
    }
}
