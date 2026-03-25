<?php

declare(strict_types=1);


namespace Core;

class Config
{
    /** @var array<string, mixed>|null */
    private static ?array $config = null;

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $configFile = __DIR__ . '/../config/config.php';
        if (!is_file($configFile)) {
            throw new \RuntimeException('Fichier de configuration manquant (config/config.php).');
        }

        $config = require $configFile;
        if (!is_array($config)) {
            throw new \RuntimeException('Le fichier config/config.php doit retourner un tableau.');
        }

        /** @var array<string, mixed> $config */
        self::$config = $config;
        return self::$config;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $config = self::all();
        return array_key_exists($key, $config) ? $config[$key] : $default;
    }
}
