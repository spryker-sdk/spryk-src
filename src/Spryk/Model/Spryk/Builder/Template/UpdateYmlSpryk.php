<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Template;

use SprykerSdk\Spryk\Exception\YmlException;
use SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface;
use SprykerSdk\Spryk\SprykConfig;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

class UpdateYmlSpryk extends AbstractBuilder
{
    /**
     * @var string
     */
    public const ARGUMENT_TEMPLATE = 'template';

    /**
     * @var string
     */
    public const ARGUMENT_AFTER_ELEMENT = 'afterElement';

    /**
     * @var string
     */
    public const ARGUMENT_ADD_TO_ELEMENT = 'addToElement';

    /**
     * @var string
     */
    public const ARGUMENT_ADD_TO_ELEMENT_TYPE = 'addToElementType';

    /**
     * @var string
     */
    public const ARGUMENT_CONTENT = 'content';

    /**
     * @var int
     */
    public const YAML_START_INLINE_LEVEL = 10;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface
     */
    protected TemplateRendererInterface $renderer;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface $fileResolver
     * @param \SprykerSdk\Spryk\Model\Spryk\Builder\Template\Renderer\TemplateRendererInterface $renderer
     */
    public function __construct(SprykConfig $config, FileResolverInterface $fileResolver, TemplateRendererInterface $renderer)
    {
        parent::__construct($config, $fileResolver);

        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'update-yml';
    }

    /**
     * @throws \SprykerSdk\Spryk\Exception\YmlException
     *
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface|null $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());

        if (!$resolved || empty($resolved->getDecodedYml())) {
            throw new YmlException(sprintf('The YML file "%s" is empty or it was not able to parse it.', $this->getTargetPath()));
        }

        $targetYml = $this->prepareTargetYaml($resolved->getDecodedYml());
        $resolved->setDecodedYml($targetYml);

        $this->log(sprintf('Updated <fg=green>%s</>', $this->getTargetPath()));
    }

    /**
     * @param array $targetYaml
     *
     * @return array
     */
    protected function prepareTargetYaml(array $targetYaml): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $addToElementType = $this->getAddToElementType();
        $addToElementPath = $this->getAddToElementPath();
        $addToElementName = $this->getAddToElementName();
        $ymlToAdd = $this->getYamlToAdd();
        $ymlToAdd = ($addToElementType === 'array') ? (array)$ymlToAdd : $ymlToAdd;

        // Add after is either a root element or null, support for adding it inside an array is not yet implemented
        $addAfter = $this->getAfterElement();

        $targetToAddYmlTo = $propertyAccessor->getValue($targetYaml, $addToElementPath);

        if ($targetToAddYmlTo) {
            $ymlToAdd = $this->mergeYmlToAddWithExistingYml($targetToAddYmlTo, $ymlToAdd);
            unset($targetYaml[$addToElementName]);
        }

        if ($addAfter) {
            $newYml = [];

            foreach ($targetYaml as $key => $value) {
                $newYml[$key] = $value;
                if ($key === $addAfter) {
                    $newYml[$addToElementName] = $ymlToAdd;
                }
            }

            return $newYml;
        }

        $propertyAccessor->setValue($targetYaml, $addToElementPath, $ymlToAdd);

        return $targetYaml;
    }

    /**
     * @param array $existingYml
     * @param array $newYml
     *
     * @return array
     */
    protected function mergeYmlToAddWithExistingYml(array $existingYml, array $newYml): array
    {
        foreach ($newYml as $key => $value) {
            if (!is_int($key) && !isset($existingYml[$key])) {
                $existingYml[$key] = $value;

                continue;
            }

            if (!is_int($key) && is_array($value)) {
                $existingYml[$key] = $this->mergeYmlToAddWithExistingYml($existingYml[$key], $newYml[$key]);

                continue;
            }

            if (!in_array($value, $existingYml)) {
                $existingYml[] = $value;
            }
        }

        return $existingYml;
    }

    /**
     * @return mixed
     */
    protected function getYamlToAdd()
    {
        if ($this->arguments->hasArgument(static::ARGUMENT_CONTENT)) {
            return $this->getStringArgument(static::ARGUMENT_CONTENT);
        }

        return $this->getDataForYml();
    }

    /**
     * @return array
     */
    protected function getDataForYml(): array
    {
        $content = $this->getContent($this->getTemplateName());

        return Yaml::parse($content);
    }

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return $this->getStringArgument(static::ARGUMENT_TEMPLATE);
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getContent(string $templateName): string
    {
        return $this->renderer->render(
            $templateName,
            $this->arguments->getArguments(),
        );
    }

    /**
     * @return string
     */
    protected function getAddToElementPath(): string
    {
        $addToElement = $this->getStringArgument(static::ARGUMENT_ADD_TO_ELEMENT);
        $addToElementFragments = explode('.', $addToElement);

        return sprintf('[%s]', implode('][', $addToElementFragments));
    }

    /**
     * @return string
     */
    protected function getAddToElementName(): string
    {
        $addToElement = $this->getStringArgument(static::ARGUMENT_ADD_TO_ELEMENT);
        $addToElementFragments = explode('.', $addToElement);

        return array_pop($addToElementFragments);
    }

    /**
     * @return ?string|null
     */
    protected function getAddToElementType(): ?string
    {
        if (!$this->arguments->hasArgument(static::ARGUMENT_ADD_TO_ELEMENT_TYPE)) {
            return null;
        }

        return $this->arguments->getArgument(static::ARGUMENT_ADD_TO_ELEMENT_TYPE)->getValue();
    }

    /**
     * @return string|null
     */
    protected function getAfterElement(): ?string
    {
        return $this->arguments->getArgument(static::ARGUMENT_AFTER_ELEMENT)->getValue();
    }
}
