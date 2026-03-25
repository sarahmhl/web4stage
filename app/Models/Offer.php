<?php

declare(strict_types=1);


namespace App\Models;

use Core\Database;
use PDO;

class Offer
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return self::search();
    }

    /**
     * @param array<string, string> $filters
     * @return array<int, array<string, mixed>>
     */
    public static function search(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $pdo = Database::getConnection();
        [$whereSql, $params] = self::buildFilterSql($filters);

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
              o.statut,
              e.nom AS entreprise_nom,
              e.ville AS entreprise_ville,
              e.secteur AS entreprise_secteur,
              e.description AS entreprise_description,
              e.email_contact,
              e.telephone_contact,
              e.site_web,
              GROUP_CONCAT(DISTINCT oc.libelle_competence ORDER BY oc.libelle_competence SEPARATOR '||') AS skills_concat,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_offre = o.id_offre
              ) AS applications_count,
              (
                SELECT COUNT(*)
                FROM wishlist_offre w
                WHERE w.id_offre = o.id_offre
              ) AS wishlist_count
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE o.statut = 'PUBLIEE'
            {$whereSql}
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, o.statut,
              e.nom, e.ville, e.secteur, e.description, e.email_contact, e.telephone_contact, e.site_web
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $offers = [];
        foreach ($rows as $row) {
            $offers[] = self::mapRowToDisplayOffer($row);
        }

        return $offers;
    }

    /**
     * @param array<string, string> $filters
     */
    public static function countMatching(array $filters = []): int
    {
        $pdo = Database::getConnection();
        [$whereSql, $params] = self::buildFilterSql($filters);

        $sql = "
            SELECT COUNT(*)
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            WHERE o.statut = 'PUBLIEE'
            {$whereSql}
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function allForManagement(): array
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
              GROUP_CONCAT(DISTINCT oc.libelle_competence ORDER BY oc.libelle_competence SEPARATOR '||') AS skills_concat
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, e.nom
            ORDER BY o.date_offre DESC, o.id_offre DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findDetail(int $offerId): ?array
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
              o.statut,
              e.nom AS entreprise_nom,
              e.ville AS entreprise_ville,
              e.secteur AS entreprise_secteur,
              e.description AS entreprise_description,
              e.email_contact,
              e.telephone_contact,
              e.site_web,
              GROUP_CONCAT(DISTINCT oc.libelle_competence ORDER BY oc.libelle_competence SEPARATOR '||') AS skills_concat,
              (
                SELECT COUNT(*)
                FROM candidature c
                WHERE c.id_offre = o.id_offre
              ) AS applications_count,
              (
                SELECT COUNT(*)
                FROM wishlist_offre w
                WHERE w.id_offre = o.id_offre
              ) AS wishlist_count
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE o.id_offre = :id_offre
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, o.statut,
              e.nom, e.ville, e.secteur, e.description, e.email_contact, e.telephone_contact, e.site_web
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_offre' => $offerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $offer = self::mapRowToDisplayOffer($row);
        $offer['description'] = (string) ($row['description'] ?? '');
        $offer['company_description'] = (string) ($row['entreprise_description'] ?? '');
        $offer['company_sector'] = (string) ($row['entreprise_secteur'] ?? '');
        $offer['company_email'] = (string) ($row['email_contact'] ?? '');
        $offer['company_phone'] = (string) ($row['telephone_contact'] ?? '');
        $offer['company_site'] = (string) ($row['site_web'] ?? '');
        $offer['applications_count'] = (int) ($row['applications_count'] ?? 0);
        $offer['wishlist_count'] = (int) ($row['wishlist_count'] ?? 0);
        return $offer;
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
              GROUP_CONCAT(DISTINCT oc.libelle_competence ORDER BY oc.libelle_competence SEPARATOR '||') AS skills_concat
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
     * @return array<int, string>
     */
    public static function allSkills(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT DISTINCT libelle_competence FROM offre_competence ORDER BY libelle_competence ASC');
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_values(array_filter(array_map(static fn ($value): string => (string) $value, $rows)));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function findByCompany(int $companyId, ?int $excludeOfferId = null, int $limit = 6): array
    {
        $pdo = Database::getConnection();
        $params = [':id_entreprise' => $companyId];
        $excludeSql = '';
        if ($excludeOfferId !== null) {
            $excludeSql = ' AND o.id_offre <> :exclude_offre';
            $params[':exclude_offre'] = $excludeOfferId;
        }

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
              o.statut,
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
                FROM wishlist_offre w
                WHERE w.id_offre = o.id_offre
              ) AS wishlist_count
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE o.id_entreprise = :id_entreprise
              AND o.statut = 'PUBLIEE'
              {$excludeSql}
            GROUP BY
              o.id_offre, o.id_entreprise, o.titre, o.description, o.base_remuneration,
              o.date_offre, o.duree_mois, o.image_path, o.statut, e.nom, e.ville, e.secteur
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT :limit
        ";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        $offers = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $offers[] = self::mapRowToDisplayOffer($row);
        }

        return $offers;
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

    public static function delete(int $offerId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM offre WHERE id_offre = :id_offre');
        $stmt->execute(['id_offre' => $offerId]);
    }

    /**
     * @return array{offers:int,companies:int,cities:int,skills:int}
     */
    public static function overviewStats(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              COUNT(DISTINCT o.id_offre) AS offers_count,
              COUNT(DISTINCT o.id_entreprise) AS companies_count,
              COUNT(DISTINCT NULLIF(e.ville, '')) AS cities_count,
              COUNT(DISTINCT oc.libelle_competence) AS skills_count
            FROM offre o
            JOIN entreprise e ON e.id_entreprise = o.id_entreprise
            LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
            WHERE o.statut = 'PUBLIEE'
        ";

        $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];
        return [
            'offers' => (int) ($row['offers_count'] ?? 0),
            'companies' => (int) ($row['companies_count'] ?? 0),
            'cities' => (int) ($row['cities_count'] ?? 0),
            'skills' => (int) ($row['skills_count'] ?? 0),
        ];
    }

    /**
     * @param array<string, string> $filters
     * @return array{0:string,1:array<string, mixed>}
     */
    private static function buildFilterSql(array $filters): array
    {
        $conditions = [];
        $params = [];

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $conditions[] = '
                AND (
                  o.titre LIKE :keyword
                  OR o.description LIKE :keyword
                  OR e.nom LIKE :keyword
                  OR e.ville LIKE :keyword
                )
            ';
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $city = trim((string) ($filters['city'] ?? ''));
        if ($city !== '') {
            $conditions[] = ' AND e.ville LIKE :city ';
            $params[':city'] = '%' . $city . '%';
        }

        $skill = trim((string) ($filters['skill'] ?? ''));
        if ($skill !== '') {
            $conditions[] = '
                AND EXISTS (
                  SELECT 1
                  FROM offre_competence oc_filter
                  WHERE oc_filter.id_offre = o.id_offre
                    AND oc_filter.libelle_competence LIKE :skill
                )
            ';
            $params[':skill'] = '%' . $skill . '%';
        }

        $duration = trim((string) ($filters['duration'] ?? ''));
        if ($duration === '2-3') {
            $conditions[] = ' AND o.duree_mois BETWEEN 2 AND 3 ';
        } elseif ($duration === '4-6') {
            $conditions[] = ' AND o.duree_mois BETWEEN 4 AND 6 ';
        } elseif ($duration === '6-plus') {
            $conditions[] = ' AND o.duree_mois >= 6 ';
        }

        return [implode('', $conditions), $params];
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

        $applicationsCount = (int) ($row['applications_count'] ?? 0);
        $wishlistCount = (int) ($row['wishlist_count'] ?? 0);

        return [
            'id' => (int) $row['id_offre'],
            'company_id' => (int) $row['id_entreprise'],
            'badge' => self::initials((string) $row['entreprise_nom']),
            'title' => (string) $row['titre'],
            'company' => (string) $row['entreprise_nom'],
            'city' => (string) ($row['entreprise_ville'] ?? ''),
            'sector' => (string) ($row['entreprise_secteur'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'duration' => self::durationLabel($row['duree_mois'] ?? null),
            'duration_months' => $row['duree_mois'] !== null ? (int) $row['duree_mois'] : null,
            'salary' => self::salaryLabel($row['base_remuneration'] ?? null),
            'salary_value' => $row['base_remuneration'] !== null ? (float) $row['base_remuneration'] : null,
            'published' => (string) $row['date_offre'],
            'start' => (string) $row['date_offre'],
            'skills' => $skills,
            'image' => $row['image_path'] ?? null,
            'applications_count' => $applicationsCount,
            'wishlist_count' => $wishlistCount,
            'tagline' => self::tagline($applicationsCount, $wishlistCount),
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

    private static function durationLabel(mixed $months): string
    {
        if ($months === null || (int) $months <= 0) {
            return 'Duree non precisee';
        }

        $value = (int) $months;
        return $value . ' mois';
    }

    private static function salaryLabel(mixed $amount): string
    {
        if ($amount === null || $amount === '') {
            return 'Selon profil';
        }

        return number_format((float) $amount, 2, ',', ' ') . ' EUR/mois';
    }

    private static function tagline(int $applicationsCount, int $wishlistCount): string
    {
        if ($applicationsCount > 0) {
            return $applicationsCount . ' candidature(s) deja enregistree(s) sur cette offre.';
        }

        if ($wishlistCount > 0) {
            return $wishlistCount . ' ajout(s) en wish-list pour cette offre.';
        }

        return 'Offre enregistree dans la base.';
    }
}

