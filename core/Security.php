<?php

declare(strict_types=1);

namespace Core;

class Security
{
    private const CSRF_SESSION_KEY = '_csrf_token';

    public static function generateCsrfToken(): string
    {
        $existingToken = $_SESSION[self::CSRF_SESSION_KEY] ?? null;
        if (is_string($existingToken) && $existingToken !== '') {
            return $existingToken;
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[self::CSRF_SESSION_KEY] = $token;
        return $token;
    }

    public static function checkCsrfToken(string $token): bool
    {
        $sessionToken = $_SESSION[self::CSRF_SESSION_KEY] ?? null;
        if (!is_string($sessionToken) || $sessionToken === '') {
            return false;
        }

        $isValid = hash_equals($sessionToken, $token);
        unset($_SESSION[self::CSRF_SESSION_KEY]);

        return $isValid;
    }
}
