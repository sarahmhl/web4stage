<?php

declare(strict_types=1);

// Bootstrap minimal pour charger les classes du projet pendant les tests PHPUnit.

if (session_status() === PHP_SESSION_NONE) {
    $sessionPath = dirname(__DIR__) . '/tests/.sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0775, true);
    }
    session_save_path($sessionPath);
    session_start();
}

$_SERVER['SCRIPT_NAME'] = '/projet web/public/index.php';
$_SERVER['REQUEST_URI'] = '/projet web/public/index.php/';

spl_autoload_register(function (string $class): void {
    $rootPath = dirname(__DIR__);
    $prefixes = [
        'Core\\' => $rootPath . '/core/',
        'App\\Controllers\\' => $rootPath . '/app/Controllers/',
        'App\\Models\\' => $rootPath . '/app/Models/',
        'Tests\\' => $rootPath . '/tests/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
            }
        }
    }
});
