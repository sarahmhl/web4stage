<?php

declare(strict_types=1);

// Ce service gere les messages flash affiches apres une action reussie ou en erreur.

namespace Core;

class Flash
{
    private const SESSION_KEY = '_flash_messages';

    public static function add(string $type, string $message): void
    {
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        $_SESSION[self::SESSION_KEY][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * @return array<int, array{type:string,message:string}>
     */
    public static function consume(): array
    {
        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        if (!is_array($messages)) {
            return [];
        }

        $normalized = [];
        foreach ($messages as $message) {
            if (!is_array($message)) {
                continue;
            }

            $type = isset($message['type']) ? (string) $message['type'] : 'info';
            $text = isset($message['message']) ? (string) $message['message'] : '';
            if ($text === '') {
                continue;
            }

            $normalized[] = [
                'type' => $type,
                'message' => $text,
            ];
        }

        return $normalized;
    }
}
