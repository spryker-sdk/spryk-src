<?php declare (strict_types=1);

gc_disable(); // performance boost

define('__SPRYK_RUNNING__', true);

$devOrPharLoader = require_once __DIR__ . '/../vendor/autoload.php';
$devOrPharLoader->unregister();

$autoloaderInWorkingDirectory = getcwd() . '/vendor/autoload.php';
$composerAutoloaderProjectPaths = [];

if (is_file($autoloaderInWorkingDirectory)) {
    $composerAutoloaderProjectPaths[] = dirname($autoloaderInWorkingDirectory, 2);

    require_once $autoloaderInWorkingDirectory;
}

$autoloadProjectAutoloaderFile = function (string $file) use (&$composerAutoloaderProjectPaths): void {
    $path = dirname(__DIR__) . $file;
    if (!extension_loaded('phar')) {
        if (is_file($path)) {
            $composerAutoloaderProjectPaths[] = dirname($path, 2);

            require_once $path;
        }
    } else {
        $pharPath = \Phar::running(false);
        if ($pharPath === '') {
            if (is_file($path)) {
                $composerAutoloaderProjectPaths[] = dirname($path, 2);

                require_once $path;
            }
        } else {
            $path = dirname($pharPath) . $file;
            if (is_file($path)) {
                $composerAutoloaderProjectPaths[] = dirname($path, 2);

                require_once $path;
            }
        }
    }
};

$autoloadProjectAutoloaderFile('/../../autoload.php');

$devOrPharLoader->register(true);

define('SPRYK_ROOT_DIR', __DIR__ . '/../');
