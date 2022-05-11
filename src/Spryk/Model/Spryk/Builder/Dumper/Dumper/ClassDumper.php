<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use SprykerSdk\Spryk\Model\Spryk\Builder\NodeVisitor\OrderStatementsInClassVisitor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class ClassDumper implements ClassDumperInterface
{
    /**
     * @var \PhpParser\PrettyPrinter\Standard
     */
    protected Standard $classPrinter;

    /**
     * @var \PhpParser\Parser
     */
    protected Parser $parser;

    /**
     * @var \PhpParser\Lexer
     */
    protected Lexer $lexer;

    /**
     * @param \PhpParser\PrettyPrinter\Standard $classPrinter
     * @param \PhpParser\Parser $parser
     * @param \PhpParser\Lexer $lexer
     */
    public function __construct(
        Standard $classPrinter,
        Parser $parser,
        Lexer $lexer
    ) {
        $this->classPrinter = $classPrinter;
        $this->parser = $parser;
        $this->lexer = $lexer;
    }

    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface> $resolvedFiles
     *
     * @return void
     */
    public function dump(array $resolvedFiles): void
    {
        if (!getenv('TESTING')) {
            $this->fixCodeStyleInFiles($resolvedFiles);

            return;
        }

        // Dump files without fixing code style, needed to speedup tests.
        foreach ($resolvedFiles as $resolved) {
            $classTokenTree = $this->orderStatementsInClass($resolved->getClassTokenTree());
            $fileContent = $this->classPrinter->printFormatPreserving(
                $classTokenTree,
                $resolved->getOriginalClassTokenTree(),
                $resolved->getTokens(),
            );
            $resolved->setContent($fileContent);
        }
    }

    /**
     * @param array $resolvedFiles
     *
     * @return void
     */
    protected function fixCodeStyleInFiles(array $resolvedFiles): void
    {
        $tmpClassNameMap = [];

        $tmpDirectory = sprintf('%s/spryk', sys_get_temp_dir());

        if (!is_dir($tmpDirectory)) {
            mkdir($tmpDirectory, 0777, true);
        }

        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface $resolved */
        foreach ($resolvedFiles as $resolved) {
            $tmpFileName = $resolved->getTmpFileName();
            $classTokenTree = $this->orderStatementsInClass($resolved->getClassTokenTree());
            $fileContent = $this->classPrinter->printFormatPreserving(
                $classTokenTree,
                $resolved->getOriginalClassTokenTree(),
                $resolved->getTokens(),
            );

            $this->filePutContent($tmpFileName, $fileContent);

            $tmpClassNameMap[$tmpFileName] = $resolved;
        }

        $process = new Process(['vendor/bin/phpcbf', $tmpDirectory, '--standard=vendor/spryker/code-sniffer/Spryker/ruleset.xml'], null, null, null, 180);
        $process->run();

        foreach ($tmpClassNameMap as $tmpFileName => $resolved) {
            $resolved->setContent((string)file_get_contents($tmpFileName));
        }

        $filesystem = new Filesystem();
        $filesystem->remove($tmpDirectory);
    }

    /**
     * @param array $statements
     *
     * @return array
     */
    protected function orderStatementsInClass(array $statements): array
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new OrderStatementsInClassVisitor());

        return $traverser->traverse($statements);
    }

    /**
     * @param string $fileName
     * @param string $fileContent
     *
     * @return void
     */
    private function filePutContent(string $fileName, string $fileContent): void
    {
        $dir = dirname($fileName);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($fileName, $fileContent);
    }
}
