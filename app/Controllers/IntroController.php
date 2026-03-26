<?php

declare(strict_types=1);


namespace App\Controllers;

use Core\Auth;
use Core\Security;
use Core\View;

class IntroController
{
    private function buildUrl(string $path): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $cleanPath = '/' . ltrim($path, '/');

        return str_replace(' ', '%20', rtrim($scriptName, '/') . $cleanPath);
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $this->buildUrl($path));
        exit;
    }

    public function index(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = (string) ($user['role'] ?? '');

            if ($role === Auth::ROLE_ETUDIANT) {
                $this->redirect('/dashboard-etudiant');
            }

            if ($role === Auth::ROLE_PILOTE) {
                $this->redirect('/dashboard-pilote');
            }

            if ($role === Auth::ROLE_ADMIN) {
                $this->redirect('/dashboard-admin');
            }
        }

        $error = $_SESSION['login_error'] ?? null;
        $success = $_SESSION['login_success'] ?? null;
        unset($_SESSION['login_error'], $_SESSION['login_success']);

        View::render('entry', [
            'title' => 'Web4Stage - Entrée',
            'error' => is_string($error) ? $error : null,
            'success' => is_string($success) ? $success : null,
            'returnTo' => 'entry',
            'redirectTo' => '',
            'intent' => '',
            'isEntryPage' => true,
            'csrfToken' => Security::generateCsrfToken(),
            'metaDescription' => 'Accès à Web4Stage, la plateforme CESI de recherche de stages et de suivi des candidatures.',
            'metaKeywords' => 'stage, cesi, web4stage, connexion, candidatures',
        ]);
    }
}

