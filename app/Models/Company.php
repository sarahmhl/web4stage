<?php

declare(strict_types=1);


namespace App\Models;

use Core\Database;
use PDO;

class Company
{
    /**
     * @param array<string, string> $filters
     * @return array<int, array<string, mixed>>
     */
    public static function allWithStats(array $filters = []): array
    {
        $pdo = Database::getConnection();
        [$whereSql, $params] = self::buildFilterSql($filters);

        $sql = "
            SELECT
              e.id_entreprise,
              e.nom,
              e.description,
              e.ville,
              e.secteur,
              e.site_web,
              e.email_contact,
              e.telephone_contact,
              COUNT(DISTINCT o.id_offre) AS offers_count,
              COUNT(DISTINCT ee.id_evaluation) AS reviews_count,
              AVG(ee.note) AS average_rating
            FROM entreprise e
            LEFT JOIN offre o ON o.id_entreprise = e.id_entreprise
            LEFT JOIN evaluation_entreprise ee ON ee.id_entreprise = e.id_entreprise
            WHERE 1=1
            {$whereSql}
            GROUP BY
              e.id_entreprise, e.nom, e.description, e.ville, e.secteur,
              e.site_web, e.email_contact, e.telephone_contact
            ORDER BY e.nom ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['average_rating'] = $row['average_rating'] !== null ? round((float) $row['average_rating'], 1) : null;
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $companyId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE id_entreprise = :id_entreprise LIMIT 1');
        $stmt->execute(['id_entreprise' => $companyId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findDetail(int $companyId): ?array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              e.id_entreprise,
              e.nom,
              e.description,
              e.ville,
              e.secteur,
              e.site_web,
              e.email_contact,
              e.telephone_contact,
              COUNT(DISTINCT o.id_offre) AS offers_count,
              COUNT(DISTINCT ee.id_evaluation) AS reviews_count,
              AVG(ee.note) AS average_rating
            FROM entreprise e
            LEFT JOIN offre o ON o.id_entreprise = e.id_entreprise
            LEFT JOIN evaluation_entreprise ee ON ee.id_entreprise = e.id_entreprise
            WHERE e.id_entreprise = :id_entreprise
            GROUP BY
              e.id_entreprise, e.nom, e.description, e.ville, e.secteur,
              e.site_web, e.email_contact, e.telephone_contact
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_entreprise' => $companyId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($company === false) {
            return null;
        }

        $company['offers'] = Offer::findByCompany($companyId, null, 12);
        $company['reviews'] = CompanyReview::forCompany($companyId);
        $company['average_rating'] = $company['average_rating'] !== null
            ? round((float) $company['average_rating'], 1)
            : null;

        return $company;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = "
            INSERT INTO entreprise (nom, description, ville, secteur, site_web, email_contact, telephone_contact)
            VALUES (:nom, :description, :ville, :secteur, :site_web, :email_contact, :telephone_contact)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => (string) $data['nom'],
            'description' => $data['description'] !== '' ? (string) $data['description'] : null,
            'ville' => $data['ville'] !== '' ? (string) $data['ville'] : null,
            'secteur' => $data['secteur'] !== '' ? (string) $data['secteur'] : null,
            'site_web' => $data['site_web'] !== '' ? (string) $data['site_web'] : null,
            'email_contact' => $data['email_contact'] !== '' ? (string) $data['email_contact'] : null,
            'telephone_contact' => $data['telephone_contact'] !== '' ? (string) $data['telephone_contact'] : null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function update(int $companyId, array $data): void
    {
        $pdo = Database::getConnection();
        $sql = "
            UPDATE entreprise
            SET
              nom = :nom,
              description = :description,
              ville = :ville,
              secteur = :secteur,
              site_web = :site_web,
              email_contact = :email_contact,
              telephone_contact = :telephone_contact
            WHERE id_entreprise = :id_entreprise
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_entreprise' => $companyId,
            'nom' => (string) $data['nom'],
            'description' => $data['description'] !== '' ? (string) $data['description'] : null,
            'ville' => $data['ville'] !== '' ? (string) $data['ville'] : null,
            'secteur' => $data['secteur'] !== '' ? (string) $data['secteur'] : null,
            'site_web' => $data['site_web'] !== '' ? (string) $data['site_web'] : null,
            'email_contact' => $data['email_contact'] !== '' ? (string) $data['email_contact'] : null,
            'telephone_contact' => $data['telephone_contact'] !== '' ? (string) $data['telephone_contact'] : null,
        ]);
    }

    public static function delete(int $companyId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM entreprise WHERE id_entreprise = :id_entreprise');
        $stmt->execute(['id_entreprise' => $companyId]);
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
                  e.nom LIKE :keyword
                  OR e.description LIKE :keyword
                  OR e.secteur LIKE :keyword
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

        $sector = trim((string) ($filters['sector'] ?? ''));
        if ($sector !== '') {
            $conditions[] = ' AND e.secteur LIKE :sector ';
            $params[':sector'] = '%' . $sector . '%';
        }

        return [implode('', $conditions), $params];
    }
}

