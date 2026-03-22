<?php

declare(strict_types=1);

// Ce routeur associe chaque URL du site a un controleur et gere les pages 404.

namespace Core;

class Router
{
    /** @var array<string, array<string, string>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$this->normalize($path)] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$this->normalize($path)] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $this->getPath();
        $path = $this->normalize($uri);

        $action = $this->routes[$method][$path] ?? null;

        if ($action === null) {
            http_response_code(404);
            View::render('errors/404', [
                'title' => 'Web4Stage - Page introuvable',
            ]);
            return;
        }

        [$class, $methodName] = explode('@', $action);

        if (!class_exists($class)) {
            throw new \RuntimeException("Contrôleur introuvable : {$class}");
        }

        $controller = new $class();

        if (!method_exists($controller, $methodName)) {
            throw new \RuntimeException("Méthode introuvable : {$class}::{$methodName}");
        }

        $controller->{$methodName}();
    }

    private function getPath(): string
    {
        $path = '/';
        if (!empty($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        }

        $path = rawurldecode($path ?: '/');

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = str_replace('\\', '/', dirname($scriptName));
        if ($baseDir !== '' && $baseDir !== '/' && str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, strlen('/index.php'));
        }

        return $path ?: '/';
    }

    private function normalize(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return rtrim($path, '/') ?: '/';
    }
}
