<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer;

use Exception;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Bridge\Twig\Extension\ProfilerExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\SerializerExtension;
use Symfony\Bridge\Twig\Extension\StopwatchExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\WebLinkExtension;
use Symfony\Bridge\Twig\Extension\YamlExtension;
use Throwable;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\LoaderInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * @var \Twig\Environment
     */
    protected Environment $renderer;

    /**
     * @var array<string, bool>
     */
    protected array $excludedExtensions = [
        ProfilerExtension::class => true,
        TranslationExtension::class => true,
        CodeExtension::class => true,
        RoutingExtension::class => true,
        YamlExtension::class => true,
        StopwatchExtension::class => true,
        HttpKernelExtension::class => true,
        HttpFoundationExtension::class => true,
        DebugExtension::class => true,
        WebLinkExtension::class => true,
        SerializerExtension::class => true,
        FormExtension::class => true,
    ];

    /**
     * @param \Twig\Environment $twig
     * @param array<\Twig\Extension\ExtensionInterface> $extensions
     */
    public function __construct(Environment $twig, array $extensions)
    {
        foreach ($extensions as $extension) {
            if ($this->isExcludedExtension($extension)) {
                continue;
            }

            if (!$twig->hasExtension(get_class($extension))) {
                $twig->addExtension($extension);
            }
        }

        $projectTemplatePath = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'spryk' . DIRECTORY_SEPARATOR . 'templates';

        if (is_dir($projectTemplatePath)) {
            /** @var \Twig\Loader\FilesystemLoader $loader */
            $loader = $twig->getLoader();
            $loader->addPath($projectTemplatePath);
        }

        $this->renderer = $twig;
    }

    /**
     * @param \Twig\Extension\ExtensionInterface $extension
     *
     * @return bool
     */
    protected function isExcludedExtension(ExtensionInterface $extension): bool
    {
        $extensionClassName = get_class($extension);

        if (isset($this->excludedExtensions[$extensionClassName])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $template
     * @param array $arguments
     *
     * @return string
     */
    public function render(string $template, array $arguments): string
    {
        return $this->renderer->render($template, $arguments);
    }

    /**
     * @param string $templateString
     * @param array $arguments
     * @param string $sprykName
     *
     * @throws \Exception
     *
     * @return string
     */
    public function renderString(string $templateString, array $arguments, string $sprykName): string
    {
        try {
            $template = $this->renderer->createTemplate($templateString);

            return $template->render($arguments);
        } catch (Throwable $e) {
            throw new Exception(sprintf('Spryk "%s" failed due to a SyntaxError in "%s"', $sprykName, $templateString), 0, $e);
        }
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public function getSource(string $template): string
    {
        /** @var \Twig\Loader\ChainLoader $loader */
        $loader = $this->getLoader();

        /** @var \Twig\Source $source */
        $source = $loader->getSourceContext($template);

        return $source->getCode();
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getLoader(): LoaderInterface
    {
        return $this->renderer->getLoader();
    }
}
