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

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $userId): ?array
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_utilisateur' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user !== false ? $user : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(?string $role = null): array
    {
        $pdo = Database::getConnection();
        $sql = '
            SELECT
              u.*,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_etudiant = u.id_utilisateur
              ) AS applications_count,
              (
                SELECT COUNT(*)
                FROM wishlist_offre w
                WHERE w.id_etudiant = u.id_utilisateur
              ) AS wishlist_count
            FROM utilisateur u
        ';

        $params = [];
        if ($role !== null && $role !== '') {
            $sql .= ' WHERE u.role = :role';
            $params['role'] = $role;
        }

        $sql .= ' ORDER BY u.role ASC, u.nom ASC, u.prenom ASC, u.id_utilisateur ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function studentsWithActivity(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              u.id_utilisateur,
              u.nom,
              u.prenom,
              u.email,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_etudiant = u.id_utilisateur
              ) AS applications_count,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_etudiant = u.id_utilisateur
                  AND c.statut IN ('ENVOYEE', 'EN_REVIEW')
              ) AS pending_count,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_etudiant = u.id_utilisateur
                  AND c.statut = 'ENTRETIEN'
              ) AS interviews_count,
              (
                SELECT COUNT(*)
                FROM wishlist_offre w
                WHERE w.id_etudiant = u.id_utilisateur
              ) AS wishlist_count
            FROM utilisateur u
            WHERE u.role = 'ETUDIANT'
            ORDER BY applications_count ASC, u.nom ASC, u.prenom ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
