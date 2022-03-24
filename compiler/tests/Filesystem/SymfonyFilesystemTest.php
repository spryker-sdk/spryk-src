<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryk\Compiler\Filesystem;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use function unlink;

final class SymfonyFilesystemTest extends TestCase
{
    /**
     * @return void
     */
    public function testExists(): void
    {
        $inner = $this->createMock(Filesystem::class);
        $inner->expects(self::once())->method('exists')->with('foo')->willReturn(true);

        $this->assertTrue((new SymfonyFilesystem($inner))->exists('foo'));
    }

    /**
     * @return void
     */
    public function testRemove(): void
    {
        $inner = $this->createMock(Filesystem::class);
        $inner->expects(self::once())->method('remove')->with('foo')->willReturn(true);

        (new SymfonyFilesystem($inner))->remove('foo');
    }

    /**
     * @return void
     */
    public function testMkdir(): void
    {
        $inner = $this->createMock(Filesystem::class);
        $inner->expects(self::once())->method('mkdir')->with('foo')->willReturn(true);

        (new SymfonyFilesystem($inner))->mkdir('foo');
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        $inner = $this->createMock(Filesystem::class);

        $content = (new SymfonyFilesystem($inner))->read(__DIR__ . '/data/composer.json');
        $this->assertSame("{}\n", $content);
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        $inner = $this->createMock(Filesystem::class);

        @unlink(__DIR__ . '/data/test.json');
        (new SymfonyFilesystem($inner))->write(__DIR__ . '/data/test.json', "{}\n");

        $this->assertFileExists(__DIR__ . '/data/test.json');
        $this->assertFileEquals(__DIR__ . '/data/composer.json', __DIR__ . '/data/test.json');
    }
}
