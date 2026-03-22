<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class Wishlist
{
    /**
     * @return array<int, int>
     */
    public static function offerIdsForStudent(int $studentId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id_offre FROM wishlist_offre WHERE id_etudiant = :id_etudiant');
        $stmt->execute([':id_etudiant' => $studentId]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_map('intval', $rows);
    }

    public static function has(int $studentId, int $offerId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT 1 FROM wishlist_offre WHERE id_etudiant = :id_etudiant AND id_offre = :id_offre LIMIT 1'
        );
        $stmt->execute([
            ':id_etudiant' => $studentId,
            ':id_offre' => $offerId,
        ]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return array{active:bool}
     */
    public static function toggle(int $studentId, int $offerId): array
    {
        $pdo = Database::getConnection();

        if (self::has($studentId, $offerId)) {
            $stmt = $pdo->prepare(
                'DELETE FROM wishlist_offre WHERE id_etudiant = :id_etudiant AND id_offre = :id_offre'
            );
            $stmt->execute([
                ':id_etudiant' => $studentId,
                ':id_offre' => $offerId,
            ]);

            return ['active' => false];
        }

        $stmt = $pdo->prepare(
            'INSERT INTO wishlist_offre (id_etudiant, id_offre) VALUES (:id_etudiant, :id_offre)'
        );
        $stmt->execute([
            ':id_etudiant' => $studentId,
            ':id_offre' => $offerId,
        ]);

        return ['active' => true];
    }

    public static function countForStudent(int $studentId): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM wishlist_offre WHERE id_etudiant = :id_etudiant');
        $stmt->execute([':id_etudiant' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function listForStudent(int $studentId): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              o.id_offre,
              o.id_entreprise,
              o.titre,
              o.description,
              o.base_remuneration,
              o.date_offre,
              o.duree_mois,
              o.image_path,
              e.nom AS entreprise_nom,
              e.ville AS entreprise_ville,
              e.secteur AS entreprise_secteur,
              GROUP_CONCAT(DISTINCT oc.libelle_competence ORDER BY oc.libelle_competence SEPARATOR '||') AS skills_concat,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_offre = o.id_offre
              ) AS applications_count,
              (
                SELECT COUNT(*)
                FROM wishlist_offre w2
                WHERE w2.id_offre = o.id_offre
              ) AS wishlist_count
            FROM wishlist_offre w
            JOIN offre o ON o.id_offre = w.id_offre
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE w.id_etudiant = :id_etudiant
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, e.nom, e.ville, e.secteur, w.created_at
            ORDER BY w.created_at DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $studentId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $offers = [];
        foreach ($rows as $row) {
            $offers[] = [
                'id' => (int) $row['id_offre'],
                'company_id' => (int) $row['id_entreprise'],
                'badge' => self::initials((string) $row['entreprise_nom']),
                'title' => (string) $row['titre'],
                'company' => (string) $row['entreprise_nom'],
                'city' => (string) ($row['entreprise_ville'] ?? ''),
                'duration' => $row['duree_mois'] !== null ? ((int) $row['duree_mois']) . ' mois' : 'Duree non precisee',
                'salary' => $row['base_remuneration'] !== null
                    ? number_format((float) $row['base_remuneration'], 2, ',', ' ') . ' EUR/mois'
                    : 'Selon profil',
                'published' => (string) $row['date_offre'],
                'image' => $row['image_path'] ?? null,
                'skills' => is_string($row['skills_concat'] ?? null) && $row['skills_concat'] !== ''
                    ? array_values(array_filter(explode('||', (string) $row['skills_concat'])))
                    : [],
                'tagline' => 'Ajoutee a votre wish-list.',
            ];
        }

        return $offers;
    }

    private static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $letters = '';
        foreach ($parts as $part) {
            $letters .= mb_strtoupper(mb_substr($part, 0, 1));
            if (mb_strlen($letters) >= 2) {
                break;
            }
        }

        return $letters !== '' ? $letters : 'WL';
    }
}
