<?php

declare(strict_types=1);


namespace Core;

use App\Models\User;
use Core\View;

class Auth
{
    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_PILOTE = 'PILOTE';
    public const ROLE_ETUDIANT = 'ETUDIANT';

    public static function login(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id_utilisateur'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $params['path'] ?: '/',
                    'domain' => $params['domain'] ?: '',
                    'secure' => (bool) $params['secure'],
                    'httponly' => (bool) $params['httponly'],
                    'samesite' => $params['samesite'] ?: 'Lax',
                ]
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @return array{id:int,nom:string,prenom:string,email:string,role:string}|null
     */
    public static function user(): ?array
    {
        /** @var array{id:int,nom:string,prenom:string,email:string,role:string}|null $user */
        $user = $_SESSION['user'] ?? null;
        return $user;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function checkRole(string $role): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === $role;
    }

    public static function requireRole(string $role): void
    {
        if (!self::check()) {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
            http_response_code(302);
            header('Location: ' . rtrim($scriptName, '/') . '/login');
            exit;
        }

        if (!self::checkRole($role)) {
            http_response_code(403);
            View::render('errors/403', [
                'title' => 'Web4Stage - Accès refusé',
            ]);
            exit;
        }
    }
}


