-- Donnees de demonstration pour alimenter le site avec des comptes, entreprises et offres de test.
-- Web4Stage seed data
SET NAMES utf8mb4;

INSERT INTO entreprise (nom, description, ville, secteur, site_web, email_contact, telephone_contact)
VALUES
  ('Tech Horizon', 'Agence web orientée produits digitaux, intégration front-end et interfaces modernes.', 'Paris', 'Developpement Web', 'https://techhorizon.example', 'contact@techhorizon.fr', '01 84 52 11 20'),
  ('Nova Media', 'Agence spécialisée en acquisition digitale, contenus social media et campagnes multicanales.', 'Lyon', 'Marketing Digital', 'https://novamedia.example', 'recrutement@novamedia.fr', '04 72 11 45 60'),
  ('Cesi Digital', 'Structure orientée projets web pédagogiques, maintenance d applications et bonnes pratiques MVC.', 'Remote', 'Ingenierie logicielle', 'https://cesidigital.example', 'stages@cesidigital.example', '02 31 00 80 20'),
  ('Altis Web', 'Studio de développement web focalisé sur les outils métiers, la maintenance et l évolutivité.', 'Bordeaux', 'Developpement Web', 'https://altisweb.example', 'jobs@altisweb.example', '05 56 44 18 22'),
  ('Studio Interface', 'Agence UX/UI centrée sur la conception d interfaces, wireframes et design systems.', 'Lille', 'UX / UI Design', 'https://studiointerface.example', 'contact@studiointerface.example', '03 20 90 14 30'),
  ('Data Insight', 'Cabinet data orienté reporting, tableaux de bord et valorisation des données métiers.', 'Toulouse', 'Data / BI', 'https://datainsight.example', 'talents@datainsight.example', '05 61 44 23 10'),
  ('Campus Events', 'Organisation d événements étudiants et accompagnement communication sur des opérations campus.', 'Paris', 'Communication', 'https://campusevents.example', 'stages@campusevents.example', '01 77 11 92 50'),
  ('Infra Secure', 'Entreprise orientée administration systèmes, supervision réseau et sécurité des postes.', 'Nantes', 'Systemes & Reseaux', 'https://infrasecure.example', 'contact@infrasecure.example', '02 40 31 88 42')
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  ville = VALUES(ville),
  secteur = VALUES(secteur),
  site_web = VALUES(site_web),
  email_contact = VALUES(email_contact),
  telephone_contact = VALUES(telephone_contact);

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

INSERT INTO document_etudiant (id_etudiant, cv_path, lettre_type)
SELECT
  u.id_utilisateur,
  'cv/lea-martin-cv.pdf',
  'Madame, Monsieur, je souhaite rejoindre une entreprise qui me permettra de développer mes compétences web tout en découvrant un environnement de stage concret.'
FROM utilisateur u
WHERE u.email = 'lea.martin@viacesi.fr'
  AND NOT EXISTS (
    SELECT 1
    FROM document_etudiant d
    WHERE d.id_etudiant = u.id_utilisateur
  );

INSERT INTO wishlist_offre (id_etudiant, id_offre)
SELECT u.id_utilisateur, o.id_offre
FROM utilisateur u
JOIN offre o ON o.titre IN (
  'Stage Developpeur Front-end',
  'Stage Developpeur Web PHP / JS',
  'Stage Marketing digital'
)
WHERE u.email = 'lea.martin@viacesi.fr'
ON DUPLICATE KEY UPDATE
  created_at = wishlist_offre.created_at;

INSERT INTO candidature (id_offre, id_etudiant, statut, commentaire, lettre_motivation, cv_path)
SELECT
  o.id_offre,
  u.id_utilisateur,
  c.statut,
  c.commentaire,
  c.lettre_motivation,
  'cv/lea-martin-cv.pdf'
FROM utilisateur u
JOIN (
  SELECT 'Stage Developpeur Front-end' AS titre, 'ENVOYEE' AS statut, 'Candidature envoyee cette semaine apres mise a jour du CV.' AS commentaire, 'Je souhaite contribuer a la mise en place de composants front-end modernes et continuer a progresser sur JavaScript.' AS lettre_motivation
  UNION ALL
  SELECT 'Stage Developpeur PHP / MVC', 'ENTRETIEN', 'Entretien prevu avec l equipe technique.', 'Votre offre PHP / MVC correspond a mon projet de stage et a mes competences en architecture web.'
  UNION ALL
  SELECT 'Stage Marketing digital', 'EN_REVIEW', 'Retour attendu sous quelques jours.', 'Je suis interessee par l aspect contenu digital et par le suivi des campagnes de communication.'
) c
JOIN offre o ON o.titre = c.titre
WHERE u.email = 'lea.martin@viacesi.fr'
ON DUPLICATE KEY UPDATE
  statut = VALUES(statut),
  commentaire = VALUES(commentaire),
  lettre_motivation = VALUES(lettre_motivation),
  cv_path = VALUES(cv_path);

INSERT INTO avis_etudiant (id_etudiant, note, commentaire)
SELECT u.id_utilisateur, seed.note, seed.commentaire
FROM utilisateur u
JOIN (
  SELECT 5 AS note, 'La plateforme rend la recherche de stage beaucoup plus claire, surtout pour suivre les candidatures deja envoyees.' AS commentaire
  UNION ALL
  SELECT 4, 'Les fiches d offres sont faciles a lire et j aime pouvoir garder mes favoris au meme endroit.'
  UNION ALL
  SELECT 5, 'Le suivi avec le pilote et les informations entreprises aident vraiment a s organiser.'
) seed
WHERE u.email = 'lea.martin@viacesi.fr'
  AND NOT EXISTS (
    SELECT 1
    FROM avis_etudiant a
    WHERE a.id_etudiant = u.id_utilisateur
      AND a.commentaire = seed.commentaire
  );

INSERT INTO evaluation_entreprise (id_entreprise, id_etudiant, note, commentaire)
SELECT e.id_entreprise, u.id_utilisateur, seed.note, seed.commentaire
FROM utilisateur u
JOIN (
  SELECT 'Tech Horizon' AS entreprise, 5 AS note, 'Processus de candidature clair, retour rapide et mission bien expliquee.' AS commentaire
  UNION ALL
  SELECT 'Nova Media', 4, 'Bonne presentation de l offre et equipe disponible pendant les echanges.'
  UNION ALL
  SELECT 'Cesi Digital', 5, 'Entreprise adaptee pour un stage web avec un cadrage pedagogique rassurant.'
) seed
JOIN entreprise e ON e.nom = seed.entreprise
WHERE u.email = 'lea.martin@viacesi.fr'
ON DUPLICATE KEY UPDATE
  note = VALUES(note),
  commentaire = VALUES(commentaire);
