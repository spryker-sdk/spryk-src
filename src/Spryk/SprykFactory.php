<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk;

use PhpParser\Lexer;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGeneratorInterface;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionCheckerInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutorInterface;
use SprykerSdk\Spryk\Model\Spryk\Fixer\SprykDefinitionFixerInterface;

// @codeCoverageIgnore
class SprykFactory
{
    /**
     * @var \PhpParser\Lexer|null
     */
    protected ?Lexer $lexer = null;

    public function __construct(
        protected SprykConfig $config,
        protected SprykExecutorInterface $executor,
        protected SprykDefinitionDumperInterface $definitionDumper,
        protected ArgumentListGeneratorInterface $argumentListGenerator,
        protected ArgumentListReaderInterface $argumentListReader,
        protected SprykConfigurationLoaderInterface $configurationLoader,
        protected SprykDefinitionFinderInterface $sprykDefinitionFinder,
        protected SprykDefinitionCheckerInterface $sprykDefinitionChecker,
        protected SprykDefinitionFixerInterface $sprykDefinitionFixer,
    ) {
    }

    public function getExecutor(): SprykExecutorInterface
    {
        return $this->executor;
    }

    public function getDefinitionDumper(): SprykDefinitionDumperInterface
    {
        return $this->definitionDumper;
    }

    public function getArgumentListGenerator(): ArgumentListGeneratorInterface
    {
        return $this->argumentListGenerator;
    }

    public function getArgumentListReader(): ArgumentListReaderInterface
    {
        return $this->argumentListReader;
    }

    public function getConfigurationLoader(): SprykConfigurationLoaderInterface
    {
        return $this->configurationLoader;
    }

    public function createClassPrinter(): Standard
    {
        return new Standard();
    }

    public function createParser(): Parser
    {
        return (new ParserFactory())->create(ParserFactory::PREFER_PHP7, $this->createLexer());
    }

    public function createLexer(): Lexer
    {
        if (!$this->lexer) {
            $this->lexer = new Emulative([
                'usedAttributes' => [
                    'comments',
                    'startLine', 'endLine',
                    'startTokenPos', 'endTokenPos',
                ],
            ]);
        }

        return $this->lexer;
    }

    public function createSprykDefinitionFinder(): SprykDefinitionFinderInterface
    {
        return $this->sprykDefinitionFinder;
    }

    public function createSprykDefinitionChecker(): SprykDefinitionCheckerInterface
    {
        return $this->sprykDefinitionChecker;
    }

    public function createSprykDefinitionFixer(): SprykDefinitionFixerInterface
    {
        return $this->sprykDefinitionFixer;
    }
}
