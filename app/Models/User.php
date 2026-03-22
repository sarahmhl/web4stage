<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class User
{
    /**
     * @return array<string, mixed>|null
     */
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT * FROM utilisateur WHERE email = :email LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user !== false ? $user : null;
    }

    public static function createStudent(string $email, string $passwordHash): int
    {
        $pdo = Database::getConnection();

        $localPart = explode('@', $email)[0] ?? 'etudiant';
        $localPart = trim($localPart);
        $defaultNom = $localPart !== '' ? ucfirst($localPart) : 'Etudiant';

        $sql = <<<SQL
            INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role)
            VALUES (:nom, :prenom, :email, :mot_de_passe, :role)
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $defaultNom,
            'prenom' => '',
            'email' => $email,
            'mot_de_passe' => $passwordHash,
            'role' => 'ETUDIANT',
        ]);

        return (int) $pdo->lastInsertId();
    }
}

