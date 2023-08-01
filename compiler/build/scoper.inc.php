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
    'exclude-files' => $stubs,
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
            $changedContent = preg_replace('/<\?php\s\n/', '<?php' . PHP_EOL, $content); // A bug produced by the PHPScoper, it adds a whitespace after the opening PHP tag.

            if ($changedContent) {
                return $changedContent;
            }

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
            return str_replace('$context[\'_seq\'] = twig_', sprintf('$context[\'_seq\'] = %s\\\\twig_', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            // $values = twig_convert_encoding($values, 'UTF-8', $charset);
            // $values = _Spryk_177ba811\twig_convert_encoding($values, 'UTF-8', $charset);
            return str_replace('= twig_', sprintf('= %s\\twig_', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            return str_replace('(twig_', sprintf('(%s\\twig_', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            return str_replace(sprintf('%s	wig_', $prefix), sprintf('(%s\\twig_', $prefix), $content);
        },
    ],
    'exclude-namespaces' => [
        'SprykerSdk\*',
        'PhpParser\*',
        'Symfony\Polyfill\Php80\*',
    ],
     'expose-global-functions' => false,
     'expose-global-classes' => false,
];
