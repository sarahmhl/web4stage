<?php

declare(strict_types=1);

// Ce modele manipule les candidatures en base : creation, listes et donnees utiles aux vues.

namespace App\Models;

use Core\Database;
use PDO;

class Application
{
    public static function countForStudent(int $studentId): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM candidature WHERE id_etudiant = :id_etudiant');
        $stmt->execute([':id_etudiant' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array{applications:int,interviews:int,pending:int,accepted:int,rejected:int}
     */
    public static function statusCountsForStudent(int $studentId): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              COUNT(*) AS applications_count,
              SUM(CASE WHEN statut = 'ENTRETIEN' THEN 1 ELSE 0 END) AS interviews_count,
              SUM(CASE WHEN statut IN ('ENVOYEE', 'EN_REVIEW') THEN 1 ELSE 0 END) AS pending_count,
              SUM(CASE WHEN statut = 'ACCEPTEE' THEN 1 ELSE 0 END) AS accepted_count,
              SUM(CASE WHEN statut = 'REFUSEE' THEN 1 ELSE 0 END) AS rejected_count
            FROM candidature
            WHERE id_etudiant = :id_etudiant
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'applications' => (int) ($row['applications_count'] ?? 0),
            'interviews' => (int) ($row['interviews_count'] ?? 0),
            'pending' => (int) ($row['pending_count'] ?? 0),
            'accepted' => (int) ($row['accepted_count'] ?? 0),
            'rejected' => (int) ($row['rejected_count'] ?? 0),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function listForStudent(int $studentId): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              c.id_candidature,
              c.statut,
              c.commentaire,
              c.lettre_motivation,
              c.cv_path,
              c.created_at,
              o.id_offre,
              o.titre,
              e.id_entreprise,
              e.nom AS entreprise_nom
            FROM candidature c
            JOIN offre o ON o.id_offre = c.id_offre
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            WHERE c.id_etudiant = :id_etudiant
            ORDER BY c.created_at DESC, c.id_candidature DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function listForPilot(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              c.id_candidature,
              c.statut,
              c.created_at,
              c.cv_path,
              o.id_offre,
              o.titre,
              e.nom AS entreprise_nom,
              u.nom AS etudiant_nom,
              u.prenom AS etudiant_prenom,
              u.email AS etudiant_email
            FROM candidature c
            JOIN offre o ON o.id_offre = c.id_offre
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            JOIN utilisateur u ON u.id_utilisateur = c.id_etudiant
            ORDER BY c.created_at DESC, c.id_candidature DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function studentsToFollowUp(): array
    {
        return User::studentsWithActivity();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function companiesToFollowUp(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              e.id_entreprise,
              e.nom,
              e.ville,
              COUNT(DISTINCT o.id_offre) AS offers_count,
              COUNT(DISTINCT c.id_candidature) AS applications_count
            FROM entreprise e
            LEFT JOIN offre o ON o.id_entreprise = e.id_entreprise
            LEFT JOIN candidature c ON c.id_offre = o.id_offre
            GROUP BY e.id_entreprise, e.nom, e.ville
            ORDER BY applications_count ASC, offers_count DESC, e.nom ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = "
            INSERT INTO candidature (
              id_offre,
              id_etudiant,
              statut,
              commentaire,
              lettre_motivation,
              cv_path
            ) VALUES (
              :id_offre,
              :id_etudiant,
              'ENVOYEE',
              :commentaire,
              :lettre_motivation,
              :cv_path
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_offre' => (int) $data['id_offre'],
            ':id_etudiant' => (int) $data['id_etudiant'],
            ':commentaire' => (string) ($data['commentaire'] ?? ''),
            ':lettre_motivation' => (string) ($data['lettre_motivation'] ?? ''),
            ':cv_path' => (string) ($data['cv_path'] ?? ''),
        ]);

        return (int) $pdo->lastInsertId();
    }
}
