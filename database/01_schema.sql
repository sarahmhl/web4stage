
SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS utilisateur (
  id_utilisateur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL,
  prenom VARCHAR(120) NOT NULL DEFAULT '',
  email VARCHAR(190) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('ADMIN','PILOTE','ETUDIANT') NOT NULL DEFAULT 'ETUDIANT',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_utilisateur_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entreprise (
  id_entreprise INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(180) NOT NULL,
  description TEXT NULL,
  ville VARCHAR(120) NULL,
  secteur VARCHAR(120) NULL,
  site_web VARCHAR(255) NULL,
  email_contact VARCHAR(190) NULL,
  telephone_contact VARCHAR(40) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_entreprise_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS offre (
  id_offre INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_entreprise INT UNSIGNED NOT NULL,
  titre VARCHAR(180) NOT NULL,
  description TEXT NULL,
  base_remuneration DECIMAL(10,2) NULL,
  date_offre DATE NOT NULL,
  duree_mois TINYINT UNSIGNED NULL,
  image_path VARCHAR(255) NULL,
  statut ENUM('PUBLIEE','BROUILLON','ARCHIVEE') NOT NULL DEFAULT 'PUBLIEE',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_offre_entreprise_titre (id_entreprise, titre),
  KEY idx_offre_date (date_offre),
  KEY idx_offre_entreprise (id_entreprise),
  CONSTRAINT fk_offre_entreprise
    FOREIGN KEY (id_entreprise) REFERENCES entreprise(id_entreprise)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS offre_competence (
  id_offre INT UNSIGNED NOT NULL,
  libelle_competence VARCHAR(120) NOT NULL,
  PRIMARY KEY (id_offre, libelle_competence),
  CONSTRAINT fk_offre_competence_offre
    FOREIGN KEY (id_offre) REFERENCES offre(id_offre)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS candidature (
  id_candidature INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_offre INT UNSIGNED NOT NULL,
  id_etudiant INT UNSIGNED NOT NULL,
  statut ENUM('ENVOYEE','EN_REVIEW','ENTRETIEN','ACCEPTEE','REFUSEE') NOT NULL DEFAULT 'ENVOYEE',
  commentaire TEXT NULL,
  lettre_motivation LONGTEXT NULL,
  cv_path VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_candidature_unique (id_offre, id_etudiant),
  KEY idx_candidature_etudiant (id_etudiant),
  CONSTRAINT fk_candidature_offre
    FOREIGN KEY (id_offre) REFERENCES offre(id_offre)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_candidature_etudiant
    FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wishlist_offre (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS avis_etudiant (
  id_avis INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_etudiant INT UNSIGNED NOT NULL,
  note TINYINT UNSIGNED NOT NULL,
  commentaire TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_avis_etudiant_student (id_etudiant),
  CONSTRAINT fk_avis_etudiant_student
    FOREIGN KEY (id_etudiant) REFERENCES utilisateur(id_utilisateur)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS evaluation_entreprise (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS document_etudiant (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

