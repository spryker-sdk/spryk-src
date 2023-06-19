<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk;

use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtender;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderPluginInterface;
use SprykerSdk\Spryk\Twig\TwigCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * Need to be defined manually as the PHAR doesn't contain a composer.json which is used by Symfony to detect the project root.
     *
     * @return string
     */
    public function getProjectDir(): string
    {
        return __DIR__ . '/..';
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AutowireArrayParameterCompilerPass());
        $container->addCompilerPass(new TwigCompilerPass());
    }

    /**
     * Gets the container class.
     *
     * @throws \InvalidArgumentException If the generated classname is invalid
     */
    protected function getContainerClass(): string
    {
        $class = static::class;
        $class = str_contains($class, "@anonymous\0") ? get_parent_class($class).str_replace('.', '_', ContainerBuilder::hash($class)) : $class;
        $class = str_replace('\\', '_', $class).ucfirst($this->environment).($this->debug ? 'Debug' : '').'Container';

        $pattern = '/_Spryk_([a-zA-Z0-9]+)_/';
        preg_match($pattern, $class, $matches);
        if (isset($matches[1])) {
            $class = str_replace("_Spryk_{$matches[1]}_", '', $class);
        }

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $class)) {
            throw new \InvalidArgumentException(sprintf('The environment "%s" contains invalid characters, it can only contain characters allowed in PHP class names.', $this->environment));
        }

        return $class;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $configExtenderChain = $container->getDefinition(SprykConfigurationExtender::class);
        $configExtenders = $container->findTaggedServiceIds(SprykConfigurationExtenderPluginInterface::class);

        foreach ($configExtenders as $serviceId => $attributes) {
            foreach ($attributes as $attr) {
                $configExtenderChain->addMethodCall('addConfigExtender', [
                    new Definition($serviceId),
                ]);
            }
        }
    }
}
