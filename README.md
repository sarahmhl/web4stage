# Web4Stage

Web4Stage est une application web de gestion d'offres de stage.
Le projet permet de consulter des offres, gérer les candidatures et proposer des interfaces selon les rôles utilisateur : étudiant, pilote et administrateur.

## Technologies
- PHP
- HTML / CSS / JavaScript
- MySQL
- Apache
- Architecture MVC

## Lancer le projet
1. Placer le projet dans `C:\xampp\htdocs\`
2. Démarrer Apache et MySQL sur XAMPP
3. Créer la base de données `web4stage`
4. Importer :
   - `database/01_schema.sql`
   - `database/02_seed.sql`
5. Ouvrir :
   `http://localhost/projet%20web/public/index.php/`

## Comptes de test
- Étudiant : `lea.martin@viacesi.fr`
- Pilote : `pilote-01@web4stage.local`
- Admin : `admin-01@web4stage.local`

Mot de passe :
`ChangeMe123!`

## Fonctionnalités principales
- Connexion selon le rôle
- Consultation des offres de stage
- Tableau de bord étudiant
- Tableau de bord pilote
- Tableau de bord administrateur
- Ajout d'offre côté pilote
- Modification d'offre côté administrateur

## Auteurs
Projet réalisé dans le cadre du projet web CESI.
