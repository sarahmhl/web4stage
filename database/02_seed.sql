-- Web4Stage seed data
SET NAMES utf8mb4;

UPDATE entreprise SET nom = 'Tech Horizon' WHERE nom = 'Tech Studio';
UPDATE entreprise SET nom = 'Nova Media' WHERE nom = 'Growth Media';
UPDATE entreprise SET nom = 'Cesi Digital' WHERE nom = 'Cesi Solutions';
UPDATE entreprise SET nom = 'Altis Web' WHERE nom = 'BubbleTech';
UPDATE entreprise SET nom = 'Studio Interface' WHERE nom = 'LovelyScreens';
UPDATE entreprise SET nom = 'Data Insight' WHERE nom = 'CandyStats';
UPDATE entreprise SET nom = 'Campus Events' WHERE nom = 'HappyCampus';
UPDATE entreprise SET nom = 'Infra Secure' WHERE nom = 'SecureRose';

INSERT INTO entreprise (nom, ville, secteur, site_web)
VALUES
  ('Tech Horizon', 'Paris', 'Developpement Web', NULL),
  ('Nova Media', 'Lyon', 'Marketing Digital', NULL),
  ('Cesi Digital', 'Remote', 'Ingenierie logicielle', NULL),
  ('Altis Web', 'Bordeaux', 'Developpement Web', NULL),
  ('Studio Interface', 'Lille', 'UX / UI Design', NULL),
  ('Data Insight', 'Toulouse', 'Data / BI', NULL),
  ('Campus Events', 'Paris', 'Communication', NULL),
  ('Infra Secure', 'Nantes', 'Systemes & Reseaux', NULL)
ON DUPLICATE KEY UPDATE
  ville = VALUES(ville),
  secteur = VALUES(secteur);

-- Optional seeded accounts (password = ChangeMe123!)
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role)
VALUES
  ('Admin', 'Web4Stage', 'admin-01@web4stage.local', '$2y$10$UqIrI1PlOQk0cIPN0RXbS.upMs7.uw5koZmADBX.4W75NvxNWNg06', 'ADMIN'),
  ('Pilote', 'CESI', 'pilote-01@web4stage.local', '$2y$10$UqIrI1PlOQk0cIPN0RXbS.upMs7.uw5koZmADBX.4W75NvxNWNg06', 'PILOTE'),
  ('Martin', 'Lea', 'lea.martin@viacesi.fr', '$2y$10$UqIrI1PlOQk0cIPN0RXbS.upMs7.uw5koZmADBX.4W75NvxNWNg06', 'ETUDIANT')
ON DUPLICATE KEY UPDATE
  nom = VALUES(nom),
  prenom = VALUES(prenom),
  mot_de_passe = VALUES(mot_de_passe),
  role = VALUES(role);

INSERT INTO offre (id_entreprise, titre, description, base_remuneration, date_offre, duree_mois, image_path, statut)
VALUES
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Tech Horizon' LIMIT 1),
    'Stage Developpeur Front-end',
    'Participation a la conception et au developpement de composants web modernes.',
    850,
    CURDATE(),
    6,
    'devfontend.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Nova Media' LIMIT 1),
    'Stage Marketing digital',
    'Animation social media, reporting campagne, creation de contenus.',
    NULL,
    CURDATE(),
    4,
    'Marketing.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Cesi Digital' LIMIT 1),
    'Stage Developpeur PHP / MVC',
    'Evolution de modules applicatifs et qualite logicielle.',
    900,
    CURDATE(),
    5,
    'devphp.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Altis Web' LIMIT 1),
    'Stage Developpeur Web PHP / JS',
    'Participation au developpement de modules web et a la maintenance evolutive de la plateforme.',
    900,
    '2026-02-02',
    6,
    'devweb.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Studio Interface' LIMIT 1),
    'Stage UX / UI Designer junior',
    'Conception de wireframes, maquettes et composants d interface pour des produits web.',
    NULL,
    '2026-01-28',
    4,
    'design.jpg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Data Insight' LIMIT 1),
    'Stage Data & BI',
    'Preparation de tableaux de bord, reporting et analyse de donnees pour les equipes metier.',
    1000,
    '2026-01-20',
    6,
    'devweb.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Campus Events' LIMIT 1),
    'Stage Communication & Events',
    'Organisation d evenements, creation de supports et animation de la communication campus.',
    600,
    '2026-01-15',
    3,
    'Marketing.jpeg',
    'PUBLIEE'
  ),
  (
    (SELECT id_entreprise FROM entreprise WHERE nom='Infra Secure' LIMIT 1),
    'Stage Admin Systemes & Reseaux',
    'Support technique, supervision des postes et participation a des scripts d automatisation.',
    900,
    '2026-01-10',
    6,
    'devphp.jpeg',
    'PUBLIEE'
  )
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  base_remuneration = VALUES(base_remuneration),
  date_offre = VALUES(date_offre),
  duree_mois = VALUES(duree_mois),
  image_path = VALUES(image_path),
  statut = VALUES(statut);

-- Competences (id lookup by title)
INSERT IGNORE INTO offre_competence (id_offre, libelle_competence)
SELECT o.id_offre, c.skill
FROM offre o
JOIN (
  SELECT 'Stage Developpeur Front-end' AS titre, 'HTML5 / CSS3' AS skill
  UNION ALL SELECT 'Stage Developpeur Front-end', 'JavaScript'
  UNION ALL SELECT 'Stage Developpeur Front-end', 'Design system'
  UNION ALL SELECT 'Stage Marketing digital', 'Reseaux sociaux'
  UNION ALL SELECT 'Stage Marketing digital', 'Canva'
  UNION ALL SELECT 'Stage Marketing digital', 'Redaction'
  UNION ALL SELECT 'Stage Developpeur PHP / MVC', 'PHP objet'
  UNION ALL SELECT 'Stage Developpeur PHP / MVC', 'MVC'
  UNION ALL SELECT 'Stage Developpeur PHP / MVC', 'MySQL'
  UNION ALL SELECT 'Stage Developpeur Web PHP / JS', 'PHP POO'
  UNION ALL SELECT 'Stage Developpeur Web PHP / JS', 'JavaScript'
  UNION ALL SELECT 'Stage Developpeur Web PHP / JS', 'MySQL'
  UNION ALL SELECT 'Stage UX / UI Designer junior', 'Wireframes'
  UNION ALL SELECT 'Stage UX / UI Designer junior', 'Figma'
  UNION ALL SELECT 'Stage UX / UI Designer junior', 'Design system'
  UNION ALL SELECT 'Stage Data & BI', 'SQL'
  UNION ALL SELECT 'Stage Data & BI', 'Power BI'
  UNION ALL SELECT 'Stage Data & BI', 'Reporting'
  UNION ALL SELECT 'Stage Communication & Events', 'Organisation evenements'
  UNION ALL SELECT 'Stage Communication & Events', 'Communication'
  UNION ALL SELECT 'Stage Admin Systemes & Reseaux', 'Linux'
  UNION ALL SELECT 'Stage Admin Systemes & Reseaux', 'Securite'
  UNION ALL SELECT 'Stage Admin Systemes & Reseaux', 'Scripts'
) c ON c.titre = o.titre;
