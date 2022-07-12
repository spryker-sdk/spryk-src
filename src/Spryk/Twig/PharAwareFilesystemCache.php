<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Twig;

use Phar;
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
     * This class ensures that when running in a PHAR archive (not writable) we don't try to write to the caches.
     * The Twig cache is created during compile time and MUST not be written at any other time.
     *
     * @codeCoverageIgnore We need to ignore this for test coverage as we can't mock the PHP PHAR extension.
     *
     * @param string $key
     * @param string $content
     *
     * @return void
     */
    public function write(string $key, string $content): void
    {
        if (Phar::running()) {
            return;
        }

        parent::write($key, $content);
    }
}
