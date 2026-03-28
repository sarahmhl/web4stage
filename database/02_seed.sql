SET NAMES utf8mb4;

INSERT INTO entreprise (nom, description, ville, secteur, site_web, email_contact, telephone_contact)
VALUES
  ('Tech Horizon', 'Agence web orientée produits digitaux, intégration front-end et interfaces modernes.', 'Paris', 'Developpement Web', 'https://techhorizon.example', 'contact@techhorizon.fr', '01 84 52 11 20'),
  ('Nova Media', 'Agence spécialisée en acquisition digitale, contenus social media et campagnes multicanales.', 'Lyon', 'Marketing Digital', 'https://novamedia.example', 'recrutement@novamedia.fr', '04 72 11 45 60'),
  ('Cesi Digital', 'Structure orientée projets web pédagogiques, maintenance d’applications et bonnes pratiques MVC.', 'Remote', 'Ingenierie logicielle', 'https://cesidigital.example', 'stages@cesidigital.example', '02 31 00 80 20'),
  ('Altis Web', 'Studio de développement web focalisé sur les outils métiers, la maintenance et l’évolutivité.', 'Bordeaux', 'Developpement Web', 'https://altisweb.example', 'jobs@altisweb.example', '05 56 44 18 22'),
  ('Studio Interface', 'Agence UX/UI centrée sur la conception d’interfaces, wireframes et design systems.', 'Lille', 'UX / UI Design', 'https://studiointerface.example', 'contact@studiointerface.example', '03 20 90 14 30'),
  ('Data Insight', 'Cabinet data orienté reporting, tableaux de bord et valorisation des données métiers.', 'Toulouse', 'Data / BI', 'https://datainsight.example', 'talents@datainsight.example', '05 61 44 23 10'),
  ('Campus Events', 'Organisation d’événements étudiants et accompagnement communication sur des opérations campus.', 'Paris', 'Communication', 'https://campusevents.example', 'stages@campusevents.example', '01 77 11 92 50'),
  ('Infra Secure', 'Entreprise orientée administration systèmes, supervision réseau et sécurité des postes.', 'Nantes', 'Systemes & Reseaux', 'https://infrasecure.example', 'contact@infrasecure.example', '02 40 31 88 42')
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  ville = VALUES(ville),
  secteur = VALUES(secteur),
  site_web = VALUES(site_web),
  email_contact = VALUES(email_contact),
  telephone_contact = VALUES(telephone_contact);

INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role)
VALUES
  ('Admin', 'Web4Stage', 'admin-01@web4stage.local', '$2y$10$7hf3vjZRsWKHmsi0zLZYw.VTY7lisi0PG8xRAMQzktHNfENBRwNkC', 'ADMIN'),
  ('Pilote', 'CESI', 'pilote-01@web4stage.local', '$2y$10$7hf3vjZRsWKHmsi0zLZYw.VTY7lisi0PG8xRAMQzktHNfENBRwNkC', 'PILOTE'),
  ('Martin', 'Lea', 'lea.martin@viacesi.fr', '$2y$10$7hf3vjZRsWKHmsi0zLZYw.VTY7lisi0PG8xRAMQzktHNfENBRwNkC', 'ETUDIANT')
ON DUPLICATE KEY UPDATE
  nom = VALUES(nom),
  prenom = VALUES(prenom),
  mot_de_passe = VALUES(mot_de_passe),
  role = VALUES(role);

INSERT INTO offre (id_entreprise, titre, description, base_remuneration, date_offre, duree_mois, image_path, statut)
VALUES
  ((SELECT id_entreprise FROM entreprise WHERE nom='Tech Horizon' LIMIT 1),'Stage Developpeur Front-end','Participation à la conception et au développement de composants web modernes.',850,CURDATE(),6,'devfontend.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Nova Media' LIMIT 1),'Stage Marketing digital','Animation social media, reporting de campagne, création de contenus.',NULL,CURDATE(),4,'Marketing.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Cesi Digital' LIMIT 1),'Stage Developpeur PHP / MVC','Évolution de modules applicatifs et amélioration de la qualité logicielle.',900,CURDATE(),5,'devphp.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Altis Web' LIMIT 1),'Stage Developpeur Web PHP / JS','Participation au développement de modules web et à la maintenance évolutive de la plateforme.',900,'2026-02-02',6,'image4.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Studio Interface' LIMIT 1),'Stage UX / UI Designer','Conception de wireframes, maquettes et composants d’interface pour des produits web.',NULL,'2026-01-28',4,'design.jpg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Data Insight' LIMIT 1),'Stage Data & BI','Préparation de tableaux de bord, reporting et analyse de données pour les équipes métier.',1000,'2026-01-20',6,'devweb.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Campus Events' LIMIT 1),'Stage Communication & Events','Organisation d’événements, création de supports et animation de la communication campus.',600,'2026-01-15',3,'Marketing.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Infra Secure' LIMIT 1),'Stage Admin Systemes & Reseaux','Support technique, supervision des postes et participation à des scripts d’automatisation.',900,'2026-01-10',6,'devphp.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Tech Horizon' LIMIT 1),'Stage Product Designer Web','Conception de parcours utilisateurs, maquettes et prototypes pour des interfaces web.',NULL,'2026-03-24',5,'stage1.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Nova Media' LIMIT 1),'Stage Content & Community Manager','Préparation du planning éditorial, animation des réseaux sociaux et suivi des performances.',700,'2026-03-23',4,'stage2.jpeg','PUBLIEE'),
  ((SELECT id_entreprise FROM entreprise WHERE nom='Data Insight' LIMIT 1),'Stage Analyste Data','Nettoyage de données, création de tableaux de bord et production d’indicateurs.',950,'2026-03-22',6,'stage3.jpeg','PUBLIEE');

-- candidatures
INSERT INTO candidature (id_offre, id_etudiant, statut, commentaire, lettre_motivation, cv_path)
SELECT o.id_offre,u.id_utilisateur,c.statut,c.commentaire,c.lettre_motivation,'cv/lea-martin-cv.pdf'
FROM utilisateur u
JOIN (
  SELECT 'Stage Developpeur Front-end','ENVOYEE','Candidature envoyée cette semaine après mise à jour du CV.','Je souhaite contribuer à la mise en place de composants front-end modernes et continuer à progresser en JavaScript.'
  UNION ALL
  SELECT 'Stage Developpeur PHP / MVC','ENTRETIEN','Entretien prévu avec l’équipe technique.','Votre offre PHP / MVC correspond à mon projet de stage et à mes compétences en architecture web.'
  UNION ALL
  SELECT 'Stage Marketing digital','EN_REVIEW','Retour attendu sous quelques jours.','Je suis intéressée par le contenu digital et le suivi des campagnes de communication.'
) c(titre,statut,commentaire,lettre_motivation)
JOIN offre o ON o.titre=c.titre
WHERE u.email='lea.martin@viacesi.fr';

-- avis étudiant
INSERT INTO avis_etudiant (id_etudiant,note,commentaire)
SELECT u.id_utilisateur,seed.note,seed.commentaire
FROM utilisateur u
JOIN (
  SELECT 5,'La plateforme rend la recherche de stage beaucoup plus claire, surtout pour suivre les candidatures déjà envoyées.'
  UNION ALL
  SELECT 4,'Les fiches d’offres sont faciles à lire et j’aime pouvoir garder mes favoris au même endroit.'
  UNION ALL
  SELECT 5,'Le suivi avec le pilote et les informations sur les entreprises aident vraiment à s’organiser.'
) seed(note,commentaire)
WHERE u.email='lea.martin@viacesi.fr';

-- evaluation entreprise
INSERT INTO evaluation_entreprise (id_entreprise,id_etudiant,note,commentaire)
SELECT e.id_entreprise,u.id_utilisateur,seed.note,seed.commentaire
FROM utilisateur u
JOIN (
  SELECT 'Tech Horizon',5,'Processus de candidature clair, retour rapide et mission bien expliquée.'
  UNION ALL
  SELECT 'Nova Media',4,'Bonne présentation de l’offre et équipe disponible pendant les échanges.'
  UNION ALL
  SELECT 'Cesi Digital',5,'Entreprise adaptée pour un stage web, avec un cadrage pédagogique rassurant.'
) seed(entreprise,note,commentaire)
JOIN entreprise e ON e.nom=seed.entreprise
WHERE u.email='lea.martin@viacesi.fr';