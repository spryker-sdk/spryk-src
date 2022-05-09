<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Resolver;

use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface;

interface FileResolverInterface
{
    /**
     * @var string
     */
    public const RESOLVE_BY_FILE_EXTENSION = 'RESOLVE_BY_FILE_EXTENSION';

    /**
     * @var string
     */
    public const RESOLVE_AS_PLAIN_FILE = 'RESOLVE_AS_PLAIN_FILE';

    /**
     * @param string $name
     * @param string $resolveStrategy
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface|null
     */
    public function resolve(string $name, string $resolveStrategy = self::RESOLVE_BY_FILE_EXTENSION): ?ResolvedInterface;

    /**
     * @return iterable<string, \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedInterface>
     */
    public function all(): iterable;

    /**
     * @param string $filePath
     * @param string $content
     *
     * @return void
     */
    public function addFile(string $filePath, string $content): void;

    /**
     * @return void
     */
    public function reset(): void;
}
