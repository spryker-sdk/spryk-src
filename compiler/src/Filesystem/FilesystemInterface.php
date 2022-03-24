<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryk\Compiler\Filesystem;

interface FilesystemInterface
{
    /**
     * @param string $dir
     *
     * @return bool
     */
    public function exists(string $dir): bool;

    /**
     * @param string $dir
     */
    public function remove(string $dir): void;

    /**
     * @param string $dir
     */
    public function mkdir(string $dir): void;

    /**
     * @param string $file
     *
     * @return string
     */
    public function read(string $file): string;

    /**
     * @param string $file
     * @param string $data
     */
    public function write(string $file, string $data): void;
}
