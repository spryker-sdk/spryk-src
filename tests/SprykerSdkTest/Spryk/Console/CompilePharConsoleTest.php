<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Console;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use SprykerSdk\Spryk\Console\CompilePharConsole;
use SprykerSdk\Spryk\SprykFacade;
use SprykerSdkTest\SprykConsoleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Console
 * @group CompilePharConsoleTest
 * Add your own group annotations below this line
 */
class CompilePharConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykConsoleTester
     */
    protected SprykConsoleTester $tester;

    /**
     * @return void
     */
    public function testBuildsAnArgumentListCacheFileCleanCacheBuildAFreshCacheAndCompilesThePhar(): void
    {
        $consoleStub = Stub::construct(CompilePharConsole::class, [
            new SprykFacade(),
        ], [
            'executeProcess' => Expected::exactly(6),
        ]);

        $tester = $this->tester->getConsoleTester($consoleStub);
        $tester->execute([]);

        $this->assertStringContainsString('Building argument list cache...', $tester->getDisplay());
        $this->assertStringContainsString('Clean the cache...', $tester->getDisplay());
        $this->assertStringContainsString('Warm up the cache...', $tester->getDisplay());
        $this->assertStringContainsString('Build the PHAR...', $tester->getDisplay());
    }
}
