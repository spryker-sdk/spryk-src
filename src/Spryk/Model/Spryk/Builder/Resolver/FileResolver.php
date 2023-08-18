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

    public function __construct(
        protected ParserInterface $classParser,
        protected ParserInterface $ymlParser,
        protected ParserInterface $jsonParser,
        protected ParserInterface $xmlParser,
        protected ParserInterface $fileParser,
    ) {
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

    protected function parseFile(string $fileName): ResolvedInterface
    {
        $extension = $this->getExtension($fileName);

        switch ($extension) {
            case 'php':
                try {
                    return $this->classParser->parse($fileName);
                } catch (FileDoesNotContainClassOrInterfaceException) {
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

        match ($extension) {
            'php' => $this->addPhpFile($filePath, $content),
            'yml', 'yaml' => $this->addYmlFile($filePath, $content),
            'json' => $this->addJsonFile($filePath, $content),
            'xml' => $this->addXmlFile($filePath, $content),
            default => $this->addDefaultFile($filePath, $content),
        };
    }

    protected function addPhpFile(string $filePath, string $content): void
    {
        try {
            /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
            $resolved = $this->classParser->parse($content);
        } catch (FileDoesNotContainClassOrInterfaceException) {
            $this->addDefaultFile($filePath, $content);

            return;
        }

        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$resolved->getClassName()] = $resolved;
    }

    protected function addYmlFile(string $filePath, string $content): void
    {
        $resolved = $this->ymlParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    protected function addJsonFile(string $filePath, string $content): void
    {
        $resolved = $this->jsonParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

    protected function addXmlFile(string $filePath, string $content): void
    {
        $resolved = $this->xmlParser->parse($content);
        $resolved->setFilePath($filePath);
        $resolved->setOriginalContent('');

        static::$resolved[$filePath] = $resolved;
    }

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
