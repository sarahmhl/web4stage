<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class Offer
{
    /**
     * Retourne toutes les offres avec nom entreprise + competences + image.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $rows = self::fetchOfferRows();
        $offers = [];

        foreach ($rows as $row) {
            $offers[] = self::mapRowToDisplayOffer($row);
        }

        return $offers;
    }

    /**
     * Retourne les offres brutes pour les pages de gestion.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function allForManagement(): array
    {
        return self::fetchOfferRows();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findForEdit(int $offerId): ?array
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
              GROUP_CONCAT(DISTINCT oc.libelle_competence SEPARATOR '||') AS skills_concat
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE o.id_offre = :id_offre
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, e.nom
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_offre' => $offerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $skills = [];
        if (is_string($row['skills_concat'] ?? null) && $row['skills_concat'] !== '') {
            $skills = array_values(array_filter(explode('||', (string) $row['skills_concat'])));
        }

        return [
            'id_offre' => (int) $row['id_offre'],
            'id_entreprise' => (int) $row['id_entreprise'],
            'titre' => (string) $row['titre'],
            'description' => (string) ($row['description'] ?? ''),
            'base_remuneration' => $row['base_remuneration'] !== null ? (string) $row['base_remuneration'] : '',
            'date_offre' => (string) $row['date_offre'],
            'duree_mois' => $row['duree_mois'] !== null ? (string) $row['duree_mois'] : '',
            'image_path' => (string) ($row['image_path'] ?? ''),
            'entreprise_nom' => (string) $row['entreprise_nom'],
            'skills' => $skills,
        ];
    }

    /**
     * @return array<int, array{id:int,nom:string}>
     */
    public static function companyOptions(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id_entreprise, nom FROM entreprise ORDER BY nom ASC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $companies = [];
        foreach ($rows as $row) {
            $companies[] = [
                'id' => (int) $row['id_entreprise'],
                'nom' => (string) $row['nom'],
            ];
        }

        return $companies;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $sql = "
                INSERT INTO offre (
                  id_entreprise,
                  titre,
                  description,
                  base_remuneration,
                  date_offre,
                  duree_mois,
                  image_path,
                  statut
                ) VALUES (
                  :id_entreprise,
                  :titre,
                  :description,
                  :base_remuneration,
                  :date_offre,
                  :duree_mois,
                  :image_path,
                  'PUBLIEE'
                )
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_entreprise' => (int) $data['id_entreprise'],
                'titre' => (string) $data['titre'],
                'description' => (string) $data['description'],
                'base_remuneration' => $data['base_remuneration'],
                'date_offre' => (string) $data['date_offre'],
                'duree_mois' => $data['duree_mois'],
                'image_path' => $data['image_path'],
            ]);

            $offerId = (int) $pdo->lastInsertId();
            self::syncSkills($pdo, $offerId, self::normalizeSkills($data['skills'] ?? ''));

            $pdo->commit();
            return $offerId;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function update(int $offerId, array $data): void
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $sql = "
                UPDATE offre
                SET
                  id_entreprise = :id_entreprise,
                  titre = :titre,
                  description = :description,
                  base_remuneration = :base_remuneration,
                  date_offre = :date_offre,
                  duree_mois = :duree_mois,
                  image_path = :image_path
                WHERE id_offre = :id_offre
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_offre' => $offerId,
                'id_entreprise' => (int) $data['id_entreprise'],
                'titre' => (string) $data['titre'],
                'description' => (string) $data['description'],
                'base_remuneration' => $data['base_remuneration'],
                'date_offre' => (string) $data['date_offre'],
                'duree_mois' => $data['duree_mois'],
                'image_path' => $data['image_path'],
            ]);

            self::syncSkills($pdo, $offerId, self::normalizeSkills($data['skills'] ?? ''));
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function fetchOfferRows(): array
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
              GROUP_CONCAT(DISTINCT oc.libelle_competence SEPARATOR '||') AS skills_concat
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, e.nom
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT 50
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function mapRowToDisplayOffer(array $row): array
    {
        $skills = [];
        if (is_string($row['skills_concat'] ?? null) && $row['skills_concat'] !== '') {
            $skills = array_values(array_filter(explode('||', (string) $row['skills_concat'])));
        }

        return [
            'id' => (int) $row['id_offre'],
            'badge' => self::initials((string) $row['entreprise_nom']),
            'title' => (string) $row['titre'],
            'company' => (string) $row['entreprise_nom'],
            'duration' => isset($row['duree_mois']) && $row['duree_mois'] !== null
                ? $row['duree_mois'] . ' mois'
                : 'Duree non precisee',
            'salary' => isset($row['base_remuneration']) && $row['base_remuneration'] !== null
                ? number_format((float) $row['base_remuneration'], 2, ',', ' ') . ' EUR/mois'
                : 'Selon profil',
            'published' => (string) $row['date_offre'],
            'start' => (string) $row['date_offre'],
            'skills' => $skills,
            'image' => $row['image_path'] ?? null,
            'tagline' => 'Offre enregistree dans la base.',
        ];
    }

    /**
     * @param string|array<int, string> $skillsInput
     * @return array<int, string>
     */
    private static function normalizeSkills(string|array $skillsInput): array
    {
        $skills = is_array($skillsInput) ? $skillsInput : explode(',', $skillsInput);
        $normalized = [];

        foreach ($skills as $skill) {
            $label = trim((string) $skill);
            if ($label === '') {
                continue;
            }

            if (!in_array($label, $normalized, true)) {
                $normalized[] = $label;
            }
        }

        return $normalized;
    }

    /**
     * @param array<int, string> $skills
     */
    private static function syncSkills(PDO $pdo, int $offerId, array $skills): void
    {
        $deleteStmt = $pdo->prepare('DELETE FROM offre_competence WHERE id_offre = :id_offre');
        $deleteStmt->execute(['id_offre' => $offerId]);

        if ($skills === []) {
            return;
        }

        $insertStmt = $pdo->prepare(
            'INSERT INTO offre_competence (id_offre, libelle_competence) VALUES (:id_offre, :libelle_competence)'
        );

        foreach ($skills as $skill) {
            $insertStmt->execute([
                'id_offre' => $offerId,
                'libelle_competence' => $skill,
            ]);
        }
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

        return $letters !== '' ? $letters : 'OF';
    }
}
