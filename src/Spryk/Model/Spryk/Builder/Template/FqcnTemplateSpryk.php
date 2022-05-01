<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Template;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Argument;

/**
 * Adds template based on PSR class loading
 */
class FqcnTemplateSpryk extends TemplateSpryk
{
    /**
     * @var string
     */
    public const DEFAULT_SOURCE_DIR = 'src/';

    /**
     * @var string
     */
    public const ARGUMENT_FQCN = 'fqcn';

    /**
     * @var string
     */
    public const ARGUMENT_SOURCE_DIR = 'sourceDir';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'fqcnTemplate';
    }

    /**
     * @return string
     */
    protected function getTargetPath(): string
    {
        $targetFqcn = str_replace('\\', '/', trim($this->getFqcn(), '\\'));
        $filePath = sprintf('%s%s.php', $this->getSourceDir(), $targetFqcn);

        $this->arguments->addArgument((new Argument())
            ->setName(static::ARGUMENT_TARGET_PATH)
            ->setValue(dirname($filePath))
        );

        $this->arguments->addArgument((new Argument())
            ->setName(static::ARGUMENT_TARGET_FILE_NAME)
            ->setValue(basename($filePath))
        );

        return parent::getTargetPath();
    }

    /**
     * @return string
     */
    protected function getFqcn(): string
    {
        return $this->arguments->getArgument(static::ARGUMENT_FQCN)->getValue();
    }

    /**
     * @return string
     */
    protected function getSourceDir(): string
    {
        return $this->arguments->hasArgument(static::ARGUMENT_SOURCE_DIR)
            ? $this->arguments->getArgument(static::ARGUMENT_SOURCE_DIR)->getValue()
            : static::DEFAULT_SOURCE_DIR;
    }
}
