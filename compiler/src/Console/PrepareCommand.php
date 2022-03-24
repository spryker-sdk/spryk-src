<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryk\Compiler\Console;

use Exception;
use Spryk\Compiler\Filesystem\FilesystemInterface;
use Spryk\ShouldNotHappenException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use function chdir;
use function dirname;
use function escapeshellarg;
use function exec;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function is_dir;
use function json_decode;
use function json_encode;
use function realpath;
use function rename;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;
use function unlink;
use function var_export;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;

final class PrepareCommand extends Command
{
    /**
     * @param \Spryk\Compiler\Filesystem\FilesystemInterface $filesystem
     * @param string $buildDir
     */
    public function __construct(
        private FilesystemInterface $filesystem,
        private string $buildDir
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('prepare')
            ->setDescription('Prepare PHAR');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->buildPreloadScript();
        $this->fixComposerJson($this->buildDir);

        return 0;
    }

    /**
     * @param string $buildDir
     *
     * @throws \Exception
     *
     * @return void
     */
    private function fixComposerJson(string $buildDir): void
    {
        $json = json_decode($this->filesystem->read($buildDir . '/composer.json'), true);

        unset($json['replace']);
        $json['name'] = 'spryker-sdk/spryk';
        $json['require']['php'] = '>=7.4';

        // simplify autoload (remove not packed build directory)
        $json['autoload']['psr-4']['SprykerSdk\\'] = 'src/';

        $encodedJson = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        if ($encodedJson === false) {
            throw new Exception('json_encode() was not successful.');
        }

        $this->filesystem->write($buildDir . '/composer.json', $encodedJson);
    }

    /**
     * @return void
     */
    private function buildPreloadScript(): void
    {
        $vendorDir = $this->buildDir . '/vendor';

        if (!is_dir($vendorDir . '/nikic/php-parser/lib/PhpParser')) {
            return;
        }

        $preloadScript = $this->buildDir . '/preload.php';
        $template = <<<'php'
<?php declare(strict_types = 1);

%s
php;
        $finder = Finder::create();
        $root = realpath(__DIR__ . '/../../..');

        if ($root === false) {
            return;
        }

        $output = '';

        foreach (
            $finder->files()->name('*.php')->in([
            $this->buildDir . '/src',
            ]) as $phpFile
        ) {
            $realPath = $phpFile->getRealPath();

            if ($realPath === false) {
                return;
            }

            $path = substr($realPath, strlen($root));
            $output .= 'require_once __DIR__ . ' . var_export($path, true) . ';' . "\n";
        }

        file_put_contents($preloadScript, sprintf($template, $output));
    }
}
