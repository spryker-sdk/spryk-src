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
     * @var \SprykerSdk\Spryk\SprykConfig
     */
    protected SprykConfig $config;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutorInterface
     */
    protected SprykExecutorInterface $executor;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface
     */
    protected SprykDefinitionDumperInterface $definitionDumper;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGeneratorInterface
     */
    protected ArgumentListGeneratorInterface $argumentListGenerator;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReaderInterface
     */
    protected ArgumentListReaderInterface $argumentListReader;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface
     */
    protected SprykConfigurationLoaderInterface $configurationLoader;

    /**
     * @var \PhpParser\Lexer|null
     */
    protected ?Lexer $lexer = null;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface
     */
    protected SprykDefinitionFinderInterface $sprykDefinitionFinder;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionCheckerInterface
     */
    protected SprykDefinitionCheckerInterface $sprykDefinitionChecker;

    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Fixer\SprykDefinitionFixerInterface
     */
    protected SprykDefinitionFixerInterface $sprykDefinitionFixer;

    /**
     * @param \SprykerSdk\Spryk\SprykConfig $config
     * @param \SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutorInterface $executor
     * @param \SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface $definitionDumper
     * @param \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGeneratorInterface $argumentListGenerator
     * @param \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReaderInterface $argumentListReader
     * @param \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface $configurationLoader
     * @param \SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface $sprykDefinitionFinder
     * @param \SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionCheckerInterface $sprykDefinitionChecker
     * @param \SprykerSdk\Spryk\Model\Spryk\Fixer\SprykDefinitionFixerInterface $sprykDefinitionFixer
     */
    public function __construct(
        SprykConfig $config,
        SprykExecutorInterface $executor,
        SprykDefinitionDumperInterface $definitionDumper,
        ArgumentListGeneratorInterface $argumentListGenerator,
        ArgumentListReaderInterface $argumentListReader,
        SprykConfigurationLoaderInterface $configurationLoader,
        SprykDefinitionFinderInterface $sprykDefinitionFinder,
        SprykDefinitionCheckerInterface $sprykDefinitionChecker,
        SprykDefinitionFixerInterface $sprykDefinitionFixer
    ) {
        $this->config = $config;
        $this->executor = $executor;
        $this->definitionDumper = $definitionDumper;
        $this->argumentListGenerator = $argumentListGenerator;
        $this->argumentListReader = $argumentListReader;
        $this->configurationLoader = $configurationLoader;
        $this->sprykDefinitionFinder = $sprykDefinitionFinder;
        $this->sprykDefinitionChecker = $sprykDefinitionChecker;
        $this->sprykDefinitionFixer = $sprykDefinitionFixer;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutorInterface
     */
    public function getExecutor(): SprykExecutorInterface
    {
        return $this->executor;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface
     */
    public function getDefinitionDumper(): SprykDefinitionDumperInterface
    {
        return $this->definitionDumper;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGeneratorInterface
     */
    public function getArgumentListGenerator(): ArgumentListGeneratorInterface
    {
        return $this->argumentListGenerator;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReaderInterface
     */
    public function getArgumentListReader(): ArgumentListReaderInterface
    {
        return $this->argumentListReader;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoaderInterface
     */
    public function getConfigurationLoader(): SprykConfigurationLoaderInterface
    {
        return $this->configurationLoader;
    }

    /**
     * @return \PhpParser\PrettyPrinter\Standard
     */
    public function createClassPrinter(): Standard
    {
        return new Standard();
    }

    /**
     * @return \PhpParser\Parser
     */
    public function createParser(): Parser
    {
        return (new ParserFactory())->create(ParserFactory::PREFER_PHP7, $this->createLexer());
    }

    /**
     * @return \PhpParser\Lexer
     */
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

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface
     */
    public function createSprykDefinitionFinder(): SprykDefinitionFinderInterface
    {
        return $this->sprykDefinitionFinder;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Checker\SprykDefinitionCheckerInterface
     */
    public function createSprykDefinitionChecker(): SprykDefinitionCheckerInterface
    {
        return $this->sprykDefinitionChecker;
    }

    /**
     * @return \SprykerSdk\Spryk\Model\Spryk\Fixer\SprykDefinitionFixerInterface
     */
    public function createSprykDefinitionFixer(): SprykDefinitionFixerInterface
    {
        return $this->sprykDefinitionFixer;
    }
}
