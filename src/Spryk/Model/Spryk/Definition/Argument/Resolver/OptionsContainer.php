<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver;

use RuntimeException;

class OptionsContainer
{
    /**
     * @var array|null
     */
    protected static $options;

    public static function setOptions(array $options): void
    {
        static::$options = $options;
    }

    public static function hasOption(string $name): bool
    {
        return static::$options && isset(static::$options[$name]);
    }

    /**
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public static function getOption(string $name)
    {
        if (static::$options === null) {
            throw new RuntimeException('Options not loaded');
        }

        return static::$options[$name];
    }

    public static function clearOptions(): void
    {
        static::$options = null;
    }
}
