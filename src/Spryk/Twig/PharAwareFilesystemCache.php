<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Twig;

use Twig\Cache\FilesystemCache;

/**
 * This class tries to write to the write to the cache but will not throw an exception if the directory or file is not writable.
 * This is needed for running Twig inside a PHAR as PHARS are not writable.
 */
class PharAwareFilesystemCache extends FilesystemCache
{
    /**
     * @param string $cache
     */
    public function __construct(string $cache = __DIR__ . '/../../../var/cache/prod/twig')
    {
        parent::__construct($cache);
    }

    /**
     * @param string $key
     * @param string $content
     *
     * @return void
     */
    public function write(string $key, string $content): void
    {
        if (!is_writable(__DIR__)) {
            return;
        }

        parent::write($key, $content);
    }
}
