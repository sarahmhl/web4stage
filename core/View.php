<?php

declare(strict_types=1);


namespace Core;

class View
{
    public const BASE_PATH = __DIR__ . '/../app/Views/';

    /**
     * Rendu d'une vue avec un layout principal.
     *
     * @param string $template    ex: 'home', 'offers/index'
     * @param array<string, mixed> $params
     */
    public static function render(string $template, array $params = []): void
    {
        $content = self::renderPartial($template, $params);

        // On passe aussi le contenu au layout
        $layoutPath = self::BASE_PATH . 'layouts/base.php';

        if (!is_file($layoutPath)) {
            throw new \RuntimeException("Layout introuvable : {$layoutPath}");
        }

        // Les variables disponibles dans le layout
        extract($params, EXTR_SKIP);
        $pageContent = $content;

        require $layoutPath;
    }

    /**
     * Rendu d'un template partiel sans layout.
     *
     * @param array<string, mixed> $params
     */
    public static function renderPartial(string $template, array $params = []): string
    {
        $path = self::BASE_PATH . $template . '.php';

        if (!is_file($path)) {
            throw new \RuntimeException("Vue introuvable : {$path}");
        }

        extract($params, EXTR_SKIP);

        ob_start();
        require $path;
        return (string) ob_get_clean();
    }
}


