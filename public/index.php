<?php

use Core\Core\ErrorManager;
use Core\Request\Csrf;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', false);

if (!function_exists('shutDownFunction')) {
    function shutDownFunction(): void
    {
        $error = error_get_last();
        if ($error) {
            new ErrorManager($error['file'], $error['line'], $error['message'], 500);
        }
    }

    register_shutdown_function('shutDownFunction');
}

require_once '../vendor/vortex-framework/vortex-framework/Core/Core/global_functions.php';

$env = Dotenv::createImmutable(__DIR__ . '/../')->load();

date_default_timezone_set($_ENV['TIME_ZONE']);

$csrf = new Csrf();

require __DIR__ . '/../Routes/routes.php';
