<?php

declare(strict_types=1);

namespace Core;

class Url
{
    public static function scriptName(): string
    {
        return str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    }

    public static function baseDir(): string
    {
        $baseDir = rtrim(str_replace('\\', '/', dirname(self::scriptName())), '/');
        return ($baseDir === '' || $baseDir === '.') ? '' : $baseDir;
    }

    public static function projectBase(): string
    {
        $baseDir = self::baseDir();
        return $baseDir === '' ? '' : rtrim(str_replace('\\', '/', dirname($baseDir)), '/');
    }

    public static function route(string $path = ''): string
    {
        $cleanPath = '/' . ltrim($path, '/');
        $url = rtrim(self::scriptName(), '/') . $cleanPath;
        return str_replace(' ', '%20', $url);
    }

    public static function currentPath(): string
    {
        $path = $_SERVER['PATH_INFO'] ?? (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
        $path = rawurldecode((string) $path);

        $baseDir = self::baseDir();
        if ($baseDir !== '' && str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, strlen('/index.php'));
        }

        return $path === '' ? '/' : $path;
    }

    public static function asset(string $path): string
    {
        $cleanPath = ltrim($path, '/');
        $projectRoot = dirname(__DIR__);
        $filePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
        $url = (self::projectBase() === '' ? '' : self::projectBase()) . '/' . $cleanPath;
        $url = str_replace(' ', '%20', $url);

        if (is_file($filePath)) {
            $url .= '?v=' . filemtime($filePath);
        }

        return $url;
    }
}
