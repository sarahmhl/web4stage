<?php

declare(strict_types=1);


namespace App\Models;

use Core\Database;
use PDO;

class StudentFeedback
{
    public static function create(int $studentId, int $rating, string $comment): void
    {
        $pdo = Database::getConnection();
        $sql = '
            INSERT INTO avis_etudiant (id_etudiant, note, commentaire)
            VALUES (:id_etudiant, :note, :commentaire)
        ';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_etudiant' => $studentId,
            ':note' => $rating,
            ':commentaire' => $comment,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function latest(int $limit = 6): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              a.id_avis,
              a.note,
              a.commentaire,
              a.created_at,
              u.nom,
              u.prenom
            FROM avis_etudiant a
            JOIN utilisateur u ON u.id_utilisateur = a.id_etudiant
            ORDER BY a.created_at DESC, a.id_avis DESC
            LIMIT :limit
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        $items = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $prenom = trim((string) ($row['prenom'] ?? ''));
            $nom = trim((string) ($row['nom'] ?? ''));
            $items[] = [
                'id' => (int) $row['id_avis'],
                'initials' => self::initials($prenom . ' ' . $nom),
                'name' => trim($prenom . ' ' . $nom),
                'role' => 'Etudiant CESI',
                'rating' => (int) $row['note'],
                'text' => (string) $row['commentaire'],
                'date' => 'Avis poste le ' . date('d/m/Y', strtotime((string) $row['created_at'])),
            ];
        }

        return $items;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
              a.id_avis,
              a.note,
              a.commentaire,
              a.created_at,
              u.nom,
              u.prenom,
              u.email
            FROM avis_etudiant a
            JOIN utilisateur u ON u.id_utilisateur = a.id_etudiant
            ORDER BY a.created_at DESC, a.id_avis DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT COUNT(*) FROM avis_etudiant');
        return (int) $stmt->fetchColumn();
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

        return $letters !== '' ? $letters : 'ET';
    }
}

