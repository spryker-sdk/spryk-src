<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$stubs = [];

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
            if (strpos($filePath, 'src/') !== 0) {
                return $content;
            }

            $content = str_replace(sprintf('\'%s\\\\r\\\\n\'', $prefix), '\'\\\\r\\\\n\'', $content);
            $content = str_replace(sprintf('\'%s\\\\', $prefix), '\'', $content);

            return $content;
        },
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, 'src/') !== 0) {
                return $content;
            }

            return str_replace(sprintf('%s\\Attribute', $prefix), 'Attribute', $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, 'src/') !== 0) {
                return $content;
            }

            return str_replace(sprintf('%s\\ReturnTypeWillChange', $prefix), 'ReturnTypeWillChange', $content);
        },
    ],
    'whitelist' => [
        'SprykerSdk\*',
    ],
    'whitelist-global-functions' => false,
    'whitelist-global-classes' => false,
];
