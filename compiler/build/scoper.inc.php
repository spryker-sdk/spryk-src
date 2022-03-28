<?php declare(strict_types=1);

require_once '../../vendor/autoload.php';

$stubs = [
    '../../vendor/composer/InstalledVersions.php',
    '../../vendor/composer/installed.php',
];

$stubFinder = \Isolated\Symfony\Component\Finder\Finder::create();

foreach ($stubFinder->files()->name('*.php')->in([
    '../../vendor/symfony/polyfill-php80',
]) as $file) {
    $stubs[] = $file->getPathName();
}

foreach ($stubFinder->files()->name('*.yml')->in([
    '../../config/spryk',
]) as $file) {
    $stubs[] = $file->getPathName();
}

if ($_SERVER['PHAR_CHECKSUM'] ?? false) {
    $prefix = '_Spryk_checksum';
} else {
    exec('git rev-parse --short HEAD', $gitCommitOutputLines, $gitExitCode);

    if ($gitExitCode !== 0) {
        exit('Could not get Git commit');
    }

    $prefix = sprintf('_Spryk_%s', $gitCommitOutputLines[0]);
}

return [
    'prefix' => $prefix,
    'finders' => [],
    'files-whitelist' => $stubs,
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            if (!in_array($filePath, [
                'bin/spryk-build',
                'bin/spryk-dump',
                'bin/spryk-run',
            ], true)) {
                return $content;
            }

            return str_replace('__DIR__ . \'/..', '\'phar://spryk.phar', $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, 'src/') !== 0) {
                return $content;
            }

            $content = str_replace(sprintf('\'%s\\\\r\\\\n\'', $prefix), '\'\\\\r\\\\n\'', $content);
            $content = str_replace(sprintf('\'%s\\\\', $prefix), '\'', $content);

            return $content;
        },
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, 'vendor/twig/twig/src/Node/ModuleNode.php') !== 0) {
                return $content;
            }

            return str_replace('use Twig', sprintf('use %s\\\\Twig', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            return str_replace('\'twig_', sprintf('\'%s\\twig_', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            return str_replace('= twig_', sprintf('= %s\\\\twig_', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            return str_replace('(twig_', sprintf('(%s\\twig_', $prefix), $content);
        },
    ],
    'whitelist' => [
        'SprykerSdk\*',
        'PhpParser\*',
        'Symfony\Polyfill\Php80\*',
    ],
    'whitelist-global-functions' => false,
    'whitelist-global-classes' => false,
];
