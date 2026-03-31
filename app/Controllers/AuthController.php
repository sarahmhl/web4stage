<?php
// Gere la connexion, l'inscription et la deconnexion des utilisateurs.

declare(strict_types=1);

namespace App\Controllers;

use Core\Auth;
use Core\Flash;
use Core\Security;
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

    private function appendQuery(string $path, array $params): string
    {
        $filtered = [];
        foreach ($params as $key => $value) {
            if (!is_scalar($value)) {
                continue;
            }

            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }

            $filtered[$key] = $value;
        }

        if ($filtered === []) {
            return $path;
        }

        return $path . (str_contains($path, '?') ? '&' : '?') . http_build_query($filtered);
    }

    private function normalizeRedirectTarget(mixed $target): ?string
    {
        if (!is_scalar($target)) {
            return null;
        }

        $target = trim((string) $target);
        if ($target === '' || str_contains($target, "\r") || str_contains($target, "\n")) {
            return null;
        }

        if (preg_match('#^(?:https?:)?//#i', $target) === 1) {
            return null;
        }

        return '/' . ltrim($target, '/');
    }

    private function authPrompt(string $intent): ?string
    {
        return match ($intent) {
            'wishlist' => 'Connectez-vous avec un compte étudiant pour ajouter cette offre à votre wish-list.',
            'apply' => 'Connectez-vous avec un compte étudiant pour postuler à cette offre.',
            default => null,
        };
    }

    private function failurePath(): string
    {
        $returnTo = trim((string) ($_POST['return_to'] ?? ''));
        if ($returnTo === 'entry') {
            return '/';
        }

        $redirectTo = $this->normalizeRedirectTarget($_POST['redirect_to'] ?? null);
        $intent = trim((string) ($_POST['intent'] ?? ''));

        return $this->appendQuery('/login', [
            'redirect' => $redirectTo !== null ? ltrim($redirectTo, '/') : '',
            'intent' => $intent,
        ]);
    }

    private function redirectAfterLogin(): void
    {
        $this->redirect('/accueil');
    }

    public function login(): void
    {
        if (Auth::check()) {
            $this->redirectAfterLogin();
        }

        $flash = $this->popFlash();
        $redirectTo = $this->normalizeRedirectTarget($_GET['redirect'] ?? null);
        $intent = trim((string) ($_GET['intent'] ?? ''));

        View::render('entry', [
            'title' => 'Web4Stage - Connexion',
            'error' => $flash['error'],
            'success' => $flash['success'],
            'authPrompt' => $this->authPrompt($intent),
            'redirectTo' => $redirectTo ?? '',
            'intent' => $intent,
            'returnTo' => '',
            'isEntryPage' => true,
            'csrfToken' => Security::generateCsrfToken(),
            'metaDescription' => 'Connexion à la plateforme Web4Stage pour accéder aux espaces étudiant, pilote et administrateur.',
            'metaKeywords' => 'connexion, web4stage, espace étudiant, espace pilote, administration',
        ]);
    }

    public function studentLogin(): void
    {
        $this->redirect('/login');
    }

    public function pilotLogin(): void
    {
        $this->redirect('/login');
    }

    public function adminLogin(): void
    {
        $this->redirect('/login');
    }

    public function showRegister(): void
    {
        $_SESSION['login_success'] = 'La création des comptes est gérée par les administrateurs.';
        $this->redirect('/');
    }

    public function register(): void
    {
        $_SESSION['login_error'] = 'La création de compte publique est désactivée sur cette version du site.';
        $this->redirect('/');
    }

    public function handleLogin(): void
    {
        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $_SESSION['login_error'] = 'Session invalide. Merci de recommencer.';
            $this->redirect($this->failurePath());
        }

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

        $this->redirectAfterLogin();
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
        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            Flash::add('error', 'Action de déconnexion invalide.');
            $this->redirect('/accueil');
        }

        Auth::logout();
        Flash::add('success', 'Vous avez été déconnecté avec succès.');
        $this->redirect('/accueil');
    }
}
