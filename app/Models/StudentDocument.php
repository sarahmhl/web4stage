<?php

declare(strict_types=1);


namespace App\Models;

use Core\Database;
use PDO;

class StudentDocument
{
    /**
     * @return array<string, mixed>|null
     */
    public static function findByStudent(int $studentId): ?array
    {
        $pdo = Database::getConnection();
        $sql = '
            SELECT *
            FROM document_etudiant
            WHERE id_etudiant = :id_etudiant
            LIMIT 1
        ';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public static function save(int $studentId, string $coverLetterTemplate, ?string $cvPath): void
    {
        $pdo = Database::getConnection();
        $existing = self::findByStudent($studentId);
        $finalCvPath = $cvPath !== null && $cvPath !== '' ? $cvPath : (string) ($existing['cv_path'] ?? '');

        $sql = "
            INSERT INTO document_etudiant (id_etudiant, cv_path, lettre_type)
            VALUES (:id_etudiant, :cv_path, :lettre_type)
            ON DUPLICATE KEY UPDATE
              cv_path = VALUES(cv_path),
              lettre_type = VALUES(lettre_type),
              updated_at = CURRENT_TIMESTAMP
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_etudiant' => $studentId,
            ':cv_path' => $finalCvPath,
            ':lettre_type' => $coverLetterTemplate,
        ]);
    }
}


