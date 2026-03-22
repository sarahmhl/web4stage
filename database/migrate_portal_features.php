<?php

declare(strict_types=1);

// Migration ponctuelle pour ajouter les tables et colonnes des nouvelles fonctionnalites du portail.

require dirname(__DIR__) . '/core/Database.php';

use Core\Database;

$pdo = Database::getConnection();

/**
 * @param array<int, string> $statements
 */
function runStatements(PDO $pdo, array $statements): void
{
    foreach ($statements as $statement) {
        $pdo->exec($statement);
    }
}

function tableExists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table_name'
    );
    $stmt->execute([':table_name' => $table]);
    return (int) $stmt->fetchColumn() > 0;
}

function columnExists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = :column_name'
    );
    $stmt->execute([
        ':table_name' => $table,
        ':column_name' => $column,
    ]);
    return (int) $stmt->fetchColumn() > 0;
}

$schemaUpdates = [];

if (!columnExists($pdo, 'entreprise', 'description')) {
    $schemaUpdates[] = 'ALTER TABLE entreprise ADD COLUMN description TEXT NULL AFTER nom';
}
if (!columnExists($pdo, 'entreprise', 'email_contact')) {
    $schemaUpdates[] = 'ALTER TABLE entreprise ADD COLUMN email_contact VARCHAR(190) NULL AFTER site_web';
}
if (!columnExists($pdo, 'entreprise', 'telephone_contact')) {
    $schemaUpdates[] = 'ALTER TABLE entreprise ADD COLUMN telephone_contact VARCHAR(40) NULL AFTER email_contact';
}
if (!columnExists($pdo, 'candidature', 'lettre_motivation')) {
    $schemaUpdates[] = 'ALTER TABLE candidature ADD COLUMN lettre_motivation LONGTEXT NULL AFTER commentaire';
}
if (!columnExists($pdo, 'candidature', 'cv_path')) {
    $schemaUpdates[] = 'ALTER TABLE candidature ADD COLUMN cv_path VARCHAR(255) NULL AFTER lettre_motivation';
}

if (!tableExists($pdo, 'wishlist_offre')) {
    $schemaUpdates[] = "
        CREATE TABLE wishlist_offre (
          id_wishlist INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          id_etudiant INT UNSIGNED NOT NULL,
          id_offre INT UNSIGNED NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY uq_wishlist_offer_student (id_etudiant, id_offre),
          KEY idx_wishlist_offer (id_offre),
          CONSTRAINT fk_wishlist_offer_student
            FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
            ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT fk_wishlist_offer_offer
            FOREIGN KEY (id_offre) REFERENCES offre(id_offre)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
}

if (!tableExists($pdo, 'avis_etudiant')) {
    $schemaUpdates[] = "
        CREATE TABLE avis_etudiant (
          id_avis INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          id_etudiant INT UNSIGNED NOT NULL,
          note TINYINT UNSIGNED NOT NULL,
          commentaire TEXT NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          KEY idx_avis_etudiant_student (id_etudiant),
          CONSTRAINT fk_avis_etudiant_student
            FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
}

if (!tableExists($pdo, 'evaluation_entreprise')) {
    $schemaUpdates[] = "
        CREATE TABLE evaluation_entreprise (
          id_evaluation INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          id_entreprise INT UNSIGNED NOT NULL,
          id_etudiant INT UNSIGNED NOT NULL,
          note TINYINT UNSIGNED NOT NULL,
          commentaire TEXT NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY uq_evaluation_company_student (id_entreprise, id_etudiant),
          KEY idx_evaluation_student (id_etudiant),
          CONSTRAINT fk_evaluation_company
            FOREIGN KEY (id_entreprise) REFERENCES entreprise(id_entreprise)
            ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT fk_evaluation_student
            FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
}

if (!tableExists($pdo, 'document_etudiant')) {
    $schemaUpdates[] = "
        CREATE TABLE document_etudiant (
          id_document INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          id_etudiant INT UNSIGNED NOT NULL,
          cv_path VARCHAR(255) NULL,
          lettre_type LONGTEXT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          UNIQUE KEY uq_document_student (id_etudiant),
          CONSTRAINT fk_document_student
            FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
}

runStatements($pdo, $schemaUpdates);

runStatements($pdo, [
    "
        UPDATE entreprise
        SET
          description = CASE nom
            WHEN 'Tech Horizon' THEN 'Agence web orientée produits digitaux, intégration front-end et interfaces modernes.'
            WHEN 'Nova Media' THEN 'Agence spécialisée en acquisition digitale, contenus social media et campagnes multicanales.'
            WHEN 'Cesi Digital' THEN 'Structure orientée projets web pédagogiques, maintenance d applications et bonnes pratiques MVC.'
            WHEN 'Altis Web' THEN 'Studio de développement web focalisé sur les outils métiers, la maintenance et l évolutivité.'
            WHEN 'Studio Interface' THEN 'Agence UX/UI centrée sur la conception d interfaces, wireframes et design systems.'
            WHEN 'Data Insight' THEN 'Cabinet data orienté reporting, tableaux de bord et valorisation des données métiers.'
            WHEN 'Campus Events' THEN 'Organisation d événements étudiants et accompagnement communication sur des opérations campus.'
            WHEN 'Infra Secure' THEN 'Entreprise orientée administration systèmes, supervision réseau et sécurité des postes.'
            ELSE description
          END,
          email_contact = CASE nom
            WHEN 'Tech Horizon' THEN 'contact@techhorizon.fr'
            WHEN 'Nova Media' THEN 'recrutement@novamedia.fr'
            WHEN 'Cesi Digital' THEN 'stages@cesidigital.fr'
            WHEN 'Altis Web' THEN 'jobs@altisweb.fr'
            WHEN 'Studio Interface' THEN 'contact@studiointerface.fr'
            WHEN 'Data Insight' THEN 'talents@datainsight.fr'
            WHEN 'Campus Events' THEN 'stages@campusevents.fr'
            WHEN 'Infra Secure' THEN 'contact@infrasecure.fr'
            ELSE email_contact
          END,
          telephone_contact = CASE nom
            WHEN 'Tech Horizon' THEN '01 84 52 11 20'
            WHEN 'Nova Media' THEN '04 72 11 45 60'
            WHEN 'Cesi Digital' THEN '02 31 00 80 20'
            WHEN 'Altis Web' THEN '05 56 44 18 22'
            WHEN 'Studio Interface' THEN '03 20 90 14 30'
            WHEN 'Data Insight' THEN '05 61 44 23 10'
            WHEN 'Campus Events' THEN '01 77 11 92 50'
            WHEN 'Infra Secure' THEN '02 40 31 88 42'
            ELSE telephone_contact
          END,
          site_web = CASE nom
            WHEN 'Tech Horizon' THEN 'https://techhorizon.example'
            WHEN 'Nova Media' THEN 'https://novamedia.example'
            WHEN 'Cesi Digital' THEN 'https://cesidigital.example'
            WHEN 'Altis Web' THEN 'https://altisweb.example'
            WHEN 'Studio Interface' THEN 'https://studiointerface.example'
            WHEN 'Data Insight' THEN 'https://datainsight.example'
            WHEN 'Campus Events' THEN 'https://campusevents.example'
            WHEN 'Infra Secure' THEN 'https://infrasecure.example'
            ELSE site_web
          END
    ",
]);

$studentId = (int) ($pdo->query("SELECT id_utilisateur FROM utilisateur WHERE email = 'lea.martin@viacesi.fr' LIMIT 1")->fetchColumn() ?: 0);

if ($studentId > 0) {
    $offerIds = [];
    $offerTitleMap = [
        'frontend' => 'Stage Developpeur Front-end',
        'marketing' => 'Stage Marketing digital',
        'php' => 'Stage Developpeur PHP / MVC',
        'web' => 'Stage Developpeur Web PHP / JS',
    ];

    foreach ($offerTitleMap as $key => $title) {
        $stmt = $pdo->prepare('SELECT id_offre FROM offre WHERE titre = :titre LIMIT 1');
        $stmt->execute([':titre' => $title]);
        $offerIds[$key] = (int) ($stmt->fetchColumn() ?: 0);
    }

    $companyIds = [];
    foreach (['Tech Horizon', 'Nova Media', 'Cesi Digital'] as $companyName) {
        $stmt = $pdo->prepare('SELECT id_entreprise FROM entreprise WHERE nom = :nom LIMIT 1');
        $stmt->execute([':nom' => $companyName]);
        $companyIds[$companyName] = (int) ($stmt->fetchColumn() ?: 0);
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM document_etudiant')->fetchColumn() === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO document_etudiant (id_etudiant, cv_path, lettre_type) VALUES (:id_etudiant, :cv_path, :lettre_type)'
        );
        $stmt->execute([
            ':id_etudiant' => $studentId,
            ':cv_path' => 'cv/lea-martin-cv.pdf',
            ':lettre_type' => 'Madame, Monsieur, je souhaite rejoindre une entreprise qui me permettra de développer mes compétences web tout en découvrant un environnement de stage concret.',
        ]);
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM wishlist_offre')->fetchColumn() === 0) {
        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO wishlist_offre (id_etudiant, id_offre) VALUES (:id_etudiant, :id_offre)'
        );
        foreach (['frontend', 'web', 'marketing'] as $key) {
            if (($offerIds[$key] ?? 0) > 0) {
                $stmt->execute([
                    ':id_etudiant' => $studentId,
                    ':id_offre' => $offerIds[$key],
                ]);
            }
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM candidature')->fetchColumn() === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO candidature (id_offre, id_etudiant, statut, commentaire, lettre_motivation, cv_path)
             VALUES (:id_offre, :id_etudiant, :statut, :commentaire, :lettre_motivation, :cv_path)'
        );

        $applications = [
            [
                'offer_id' => $offerIds['frontend'] ?? 0,
                'statut' => 'ENVOYEE',
                'commentaire' => 'Candidature envoyée cette semaine après mise à jour du CV.',
                'lettre' => 'Je souhaite contribuer à la mise en place de composants front-end modernes et continuer à progresser sur JavaScript.',
                'cv' => 'cv/lea-martin-cv.pdf',
            ],
            [
                'offer_id' => $offerIds['php'] ?? 0,
                'statut' => 'ENTRETIEN',
                'commentaire' => 'Entretien prévu avec l équipe technique.',
                'lettre' => 'Votre offre PHP / MVC correspond à mon projet de stage et à mes compétences en architecture web.',
                'cv' => 'cv/lea-martin-cv.pdf',
            ],
            [
                'offer_id' => $offerIds['marketing'] ?? 0,
                'statut' => 'EN_REVIEW',
                'commentaire' => 'Retour attendu sous quelques jours.',
                'lettre' => 'Je suis intéressée par l aspect contenu digital et par le suivi des campagnes de communication.',
                'cv' => 'cv/lea-martin-cv.pdf',
            ],
        ];

        foreach ($applications as $application) {
            if ((int) $application['offer_id'] <= 0) {
                continue;
            }

            $stmt->execute([
                ':id_offre' => (int) $application['offer_id'],
                ':id_etudiant' => $studentId,
                ':statut' => $application['statut'],
                ':commentaire' => $application['commentaire'],
                ':lettre_motivation' => $application['lettre'],
                ':cv_path' => $application['cv'],
            ]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM avis_etudiant')->fetchColumn() === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO avis_etudiant (id_etudiant, note, commentaire) VALUES (:id_etudiant, :note, :commentaire)'
        );

        $feedbacks = [
            [5, 'La plateforme rend la recherche de stage beaucoup plus claire, surtout pour suivre les candidatures déjà envoyées.'],
            [4, 'Les fiches d offres sont faciles à lire et j aime pouvoir garder mes favoris au même endroit.'],
            [5, 'Le suivi avec le pilote et les informations entreprises aident vraiment à s organiser.'],
        ];

        foreach ($feedbacks as [$note, $commentaire]) {
            $stmt->execute([
                ':id_etudiant' => $studentId,
                ':note' => $note,
                ':commentaire' => $commentaire,
            ]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM evaluation_entreprise')->fetchColumn() === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO evaluation_entreprise (id_entreprise, id_etudiant, note, commentaire)
             VALUES (:id_entreprise, :id_etudiant, :note, :commentaire)'
        );

        $reviews = [
            ['Tech Horizon', 5, 'Processus de candidature clair, retour rapide et mission bien expliquée.'],
            ['Nova Media', 4, 'Bonne présentation de l offre et équipe disponible pendant les échanges.'],
            ['Cesi Digital', 5, 'Entreprise adaptée pour un stage web avec un cadrage pédagogique rassurant.'],
        ];

        foreach ($reviews as [$companyName, $note, $commentaire]) {
            $companyId = (int) ($companyIds[$companyName] ?? 0);
            if ($companyId <= 0) {
                continue;
            }

            $stmt->execute([
                ':id_entreprise' => $companyId,
                ':id_etudiant' => $studentId,
                ':note' => $note,
                ':commentaire' => $commentaire,
            ]);
        }
    }
}

echo "Migration portail appliquee.\n";
