<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryk\Compiler\Filesystem;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use function file_get_contents;
use function file_put_contents;

final class SymfonyFilesystem implements FilesystemInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    public function exists(string $dir): bool
    {
        return $this->filesystem->exists($dir);
    }

    /**
     * @param string $dir
     *
     * @return void
     */
    public function remove(string $dir): void
    {
        $this->filesystem->remove($dir);
    }

    /**
     * @param string $dir
     *
     * @return void
     */
    public function mkdir(string $dir): void
    {
        $this->filesystem->mkdir($dir);
    }

    /**
     * @param string $file
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function read(string $file): string
    {
        $content = file_get_contents($file);

        if ($content === false) {
            throw new RuntimeException();
        }

        return $content;
    }

    /**
     * @param string $file
     * @param string $data
     *
     * @return void
     */
    public function write(string $file, string $data): void
    {
        file_put_contents($file, $data);
    }
}
