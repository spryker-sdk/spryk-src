<?php declare (strict_types=1);

gc_disable(); // performance boost

define('__SPRYK_RUNNING__', true);

const ENV_PROD = 'prod';
const ENV_DEV = 'dev';
const ENV_TEST = 'test';

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
        $pharPath = Phar::running(false);
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

$applicationEnv = getenv('APPLICATION_ENV');
$allowedApplicationEnvs = [
    ENV_PROD,
    ENV_DEV,
    ENV_TEST
];

defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', getcwd());
define('APPLICATION_ENV', $applicationEnv !== false && in_array($applicationEnv, $allowedApplicationEnvs, true) ? $applicationEnv : ENV_PROD);
define('APPLICATION_DEBUG', getenv('APPLICATION_DEBUG') !== false ? (bool)getenv('APPLICATION_DEBUG') :  APPLICATION_ENV !== ENV_PROD);
