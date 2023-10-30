<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Template;

use Exception;
use PhpParser\Error;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface;
use SprykerSdk\Spryk\SprykConfig;

class TemplateSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const ARGUMENT_TARGET_FILE_NAME = 'targetFilename';

    /**
     * @var string
     */
    public const ARGUMENT_TEMPLATE = 'template';

    /**
     * @var string
     */
    public const ARGUMENT_SUB_DIRECTORY = 'subDirectory';

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface $renderer
     */
    public function __construct(SprykConfig $config, FileResolverInterface $fileResolver, protected TemplateRendererInterface $renderer)
    {
        parent::__construct($config, $fileResolver);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'template';
    }

    /**
     * @return bool
     */
    protected function shouldBuild(): bool
    {
        $targetPath = $this->getTargetPath();
        $resolved = $this->fileResolver->resolve($targetPath);

        if ($resolved === null) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function build(): void
    {
        $targetPath = $this->getTargetPath();

        if ($this->fileResolver->resolve($targetPath)) {
            throw new Exception(sprintf('Trying to add a file from template failed, because the file "%s" already exists', $targetPath));
        }

        $templateName = $this->getTemplateName();

        $targetDirectory = dirname($targetPath);

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
            $this->log(sprintf('Created <fg=green>%s</>', $targetDirectory));
        }

        $content = $this->getContent($templateName);

        try {
            $this->fileResolver->addFile($targetPath, $content);
        } catch (Error $e) {
            throw new Exception(sprintf('Could not parse content for Spryk "%s".', $this->getSprykName()), 0, $e);
        }

        $this->log(sprintf('Created <fg=green>%s</>', $targetPath));
    }

    /**
     * @return string
     */
    protected function getTargetPath(): string
    {
        $targetPath = parent::getTargetPath();

        $targetPath = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $fileName = $this->getFilename();
        $subDirectory = $this->getSubDirectory();

        return $targetPath . $subDirectory . $fileName;
    }

    protected function getFilename(): string
    {
        if ($this->arguments->hasArgument(static::ARGUMENT_TARGET_FILE_NAME)) {
            return $this->getStringArgument(static::ARGUMENT_TARGET_FILE_NAME);
        }

        return $this->getFilenameFromTemplateName();
    }

    protected function getSubDirectory(): string
    {
        if ($this->arguments->hasArgument(static::ARGUMENT_SUB_DIRECTORY) && $this->arguments->getArgument(static::ARGUMENT_SUB_DIRECTORY)->getValue() !== null) {
            return rtrim($this->getStringArgument(static::ARGUMENT_SUB_DIRECTORY), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        return '';
    }

    protected function getTemplateName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_TEMPLATE);
    }

    protected function getContent(string $templateName): string
    {
        if (isset($this->definition->getConfig()['noRender'])) {
            return $this->renderer->getSource($templateName);
        }

        return $this->renderer->render(
            $templateName,
            $this->arguments->getArguments(),
        );
    }

    protected function getFilenameFromTemplateName(): string
    {
        $filename = str_replace('.twig', '', $this->getTemplateName());
        if (strpos($filename, '/') !== false) {
            $filenameFragments = explode('/', $filename);
            $filename = array_pop($filenameFragments);
        }

        return $filename;
    }
}
