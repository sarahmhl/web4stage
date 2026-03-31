<?php

declare(strict_types=1);


namespace App\Models;

use Core\Database;
use PDO;

class CompanyReview
{
    public static function create(int $studentId, int $companyId, int $rating, string $comment): void
    {
        $pdo = Database::getConnection();
        $sql = "
            INSERT INTO evaluation_entreprise (id_entreprise, id_etudiant, note, commentaire)
            VALUES (:id_entreprise, :id_etudiant, :note, :commentaire)
            ON DUPLICATE KEY UPDATE
              note = VALUES(note),
              commentaire = VALUES(commentaire),
              created_at = CURRENT_TIMESTAMP
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_entreprise' => $companyId,
            ':id_etudiant' => $studentId,
            ':note' => $rating,
            ':commentaire' => $comment,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forCompany(int $companyId): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              ee.id_evaluation,
              ee.note,
              ee.commentaire,
              ee.created_at,
              u.nom,
              u.prenom
            FROM evaluation_entreprise ee
            JOIN utilisateur u ON u.id_utilisateur = ee.id_etudiant
            WHERE ee.id_entreprise = :id_entreprise
            ORDER BY ee.created_at DESC, ee.id_evaluation DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_entreprise' => $companyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              ee.id_evaluation,
              ee.note,
              ee.commentaire,
              ee.created_at,
              e.nom AS entreprise_nom,
              u.nom,
              u.prenom,
              u.email
            FROM evaluation_entreprise ee
            JOIN entreprise e ON e.id_entreprise = ee.id_entreprise
            JOIN utilisateur u ON u.id_utilisateur = ee.id_etudiant
            ORDER BY ee.created_at DESC, ee.id_evaluation DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


