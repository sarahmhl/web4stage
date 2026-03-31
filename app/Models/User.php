<?php
// Modele utilisateur : comptes, roles et operations de gestion des profils.

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

        return self::create([
            'nom' => $defaultNom,
            'prenom' => '',
            'email' => $email,
            'mot_de_passe' => $passwordHash,
            'role' => 'ETUDIANT',
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = <<<SQL
            INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role)
            VALUES (:nom, :prenom, :email, :mot_de_passe, :role)
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => (string) $data['nom'],
            'prenom' => (string) ($data['prenom'] ?? ''),
            'email' => (string) $data['email'],
            'mot_de_passe' => (string) $data['mot_de_passe'],
            'role' => (string) $data['role'],
        ]);

        return (int) $pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function update(int $userId, array $data): void
    {
        $pdo = Database::getConnection();

        $fields = [
            'nom = :nom',
            'prenom = :prenom',
            'email = :email',
            'role = :role',
        ];
        $params = [
            'id_utilisateur' => $userId,
            'nom' => (string) $data['nom'],
            'prenom' => (string) ($data['prenom'] ?? ''),
            'email' => (string) $data['email'],
            'role' => (string) $data['role'],
        ];

        if (!empty($data['mot_de_passe'])) {
            $fields[] = 'mot_de_passe = :mot_de_passe';
            $params['mot_de_passe'] = (string) $data['mot_de_passe'];
        }

        $sql = 'UPDATE utilisateur SET ' . implode(', ', $fields) . ' WHERE id_utilisateur = :id_utilisateur';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete(int $userId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur');
        $stmt->execute(['id_utilisateur' => $userId]);
    }

    public static function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT 1 FROM utilisateur WHERE email = :email';
        $params = ['email' => $email];

        if ($excludeUserId !== null) {
            $sql .= ' AND id_utilisateur <> :exclude_user_id';
            $params['exclude_user_id'] = $excludeUserId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() !== false;
    }

    public static function countByRole(string $role): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE role = :role');
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetchColumn();
    }
}

