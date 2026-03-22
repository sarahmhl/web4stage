<?php

declare(strict_types=1);

// Ce controleur centralise la connexion, la deconnexion et la redirection selon le role de l utilisateur.

namespace App\Controllers;

use Core\Auth;
use Core\View;

class AuthController
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

    /**
     * @return array{error:string|null, success:string|null}
     */
    private function popFlash(): array
    {
        $error = $_SESSION['login_error'] ?? null;
        $success = $_SESSION['login_success'] ?? null;
        unset($_SESSION['login_error'], $_SESSION['login_success']);

        return [
            'error' => is_string($error) ? $error : null,
            'success' => is_string($success) ? $success : null,
        ];
    }

    private function failurePath(): string
    {
        $returnTo = trim((string) ($_POST['return_to'] ?? ''));

        if ($returnTo === 'entry') {
            return '/';
        }

        return '/login';
    }

    private function redirectToDashboardByRole(string $role): void
    {
        if ($role === Auth::ROLE_ETUDIANT) {
            $this->redirect('/dashboard-etudiant');
        }

        if ($role === Auth::ROLE_PILOTE) {
            $this->redirect('/dashboard-pilote');
        }

        if ($role === Auth::ROLE_ADMIN) {
            $this->redirect('/dashboard-admin');
        }

        $this->redirect('/accueil');
    }

    public function login(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->redirectToDashboardByRole((string) ($user['role'] ?? ''));
        }

        $flash = $this->popFlash();

        View::render('auth/login', [
            'title' => 'Web4Stage - Connexion',
            'error' => $flash['error'],
            'success' => $flash['success'],
        ]);
    }

    public function studentLogin(): void
    {
        $this->redirect('/');
    }

    public function pilotLogin(): void
    {
        $this->redirect('/');
    }

    public function adminLogin(): void
    {
        $this->redirect('/');
    }

    public function showRegister(): void
    {
        $_SESSION['login_success'] = 'La création des comptes est gérée par les administrateurs et les pilotes.';
        $this->redirect('/');
    }

    public function register(): void
    {
        $_SESSION['login_error'] = 'La création de compte publique est désactivée sur cette version du site.';
        $this->redirect('/');
    }

    public function handleLogin(): void
    {
        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $failurePath = $this->failurePath();

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Merci de remplir tous les champs.';
            $this->redirect($failurePath);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['login_error'] = 'Adresse e-mail invalide.';
            $this->redirect($failurePath);
        }

        if (!Auth::login($email, $password)) {
            $_SESSION['login_error'] = 'Identifiants incorrects.';
            $this->redirect($failurePath);
        }

        $user = Auth::user();
        $this->redirectToDashboardByRole((string) ($user['role'] ?? ''));
    }

    public function handleStudentLogin(): void
    {
        $this->handleLogin();
    }

    public function handlePilotLogin(): void
    {
        $this->handleLogin();
    }

    public function handleAdminLogin(): void
    {
        $this->handleLogin();
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
