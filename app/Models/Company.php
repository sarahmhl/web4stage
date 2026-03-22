<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class Company
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function allWithStats(): array
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
            GROUP BY
              e.id_entreprise, e.nom, e.description, e.ville, e.secteur,
              e.site_web, e.email_contact, e.telephone_contact
            ORDER BY e.nom ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}
