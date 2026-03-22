# Web4Stage

## Presentation du projet

Web4Stage est une application web realisee dans le cadre du projet web CESI.
Le site a pour objectif de faciliter la recherche de stage des etudiants en regroupant des offres de stage, des informations sur les entreprises et un suivi des candidatures.

Ce projet repond au besoin suivant :
- centraliser les offres de stage
- enregistrer les entreprises ayant deja accueilli des stagiaires
- aider les etudiants a mieux suivre leur recherche
- proposer des interfaces adaptees selon le role de l'utilisateur

## But du projet

L'objectif principal est de proposer une plateforme simple et pratique qui permet :
- a l'etudiant de rechercher des offres, suivre ses candidatures et gerer sa wish-list
- au pilote de promotion de suivre les candidatures de ses etudiants et d'ajouter des offres
- a l'administrateur de superviser la plateforme, les comptes et les offres

Le site doit aussi respecter plusieurs contraintes de l'enonce :
- architecture MVC
- responsive design
- HTML, CSS, JavaScript, PHP et base SQL
- authentification et gestion des roles
- bonnes pratiques de securite
- optimisation SEO de base

## Enonce resume

Les etudiants effectuent souvent leurs recherches de stage en utilisant leurs reseaux personnels et professionnels, puis en postulant a des offres.
Le projet consiste a creer un site web capable de regrouper ces offres et de stocker les donnees des entreprises qui recherchent des stagiaires ou qui en ont deja accueilli.

Le site doit permettre de :
- rechercher et afficher des entreprises
- creer, modifier et supprimer des entreprises
- rechercher et afficher des offres de stage
- creer, modifier et supprimer des offres
- gerer les comptes pilotes et etudiants
- permettre aux etudiants de postuler a une offre
- suivre les candidatures
- gerer une wish-list d'offres
- afficher des statistiques et des informations utiles

## Profils utilisateurs

Le projet repose sur trois profils principaux :

### Etudiant
- consulter les offres
- ajouter des offres a sa wish-list
- postuler a une offre
- suivre ses candidatures

### Pilote
- suivre la promotion
- consulter les candidatures des etudiants
- ajouter des offres de stage

### Administrateur
- gerer les comptes
- superviser les offres
- modifier les offres de stage
- administrer la plateforme

## Fonctionnalites actuellement presentes

- page d'entree avec connexion
- redirection automatique selon le role du compte
- page d'accueil du projet
- page des offres de stage avec pagination
- dashboard etudiant
- dashboard pilote
- dashboard administrateur
- ajout d'offre dans l'espace pilote
- modification d'offre dans l'espace administrateur

## Technologies utilisees

- PHP
- HTML5
- CSS3
- JavaScript
- MySQL
- Apache
- architecture MVC

## Lancer le projet en local

1. Placer le projet dans `C:\xampp\htdocs\projet web`
2. Demarrer Apache et MySQL sur XAMPP
3. Creer la base de donnees `web4stage`
4. Importer :
   - `database/01_schema.sql`
   - `database/02_seed.sql`
5. Ouvrir dans le navigateur :
   `http://localhost/projet%20web/public/index.php/`

## Comptes de test

- Etudiant : `lea.martin@viacesi.fr`
- Pilote : `pilote-01@web4stage.local`
- Admin : `admin-01@web4stage.local`

Mot de passe :
`ChangeMe123!`

## Cadre du projet

Ce projet est realise dans un contexte pedagogique.
Il s'inscrit dans le cahier des charges CESI autour de la gestion des stages, des utilisateurs, des offres et des candidatures.

