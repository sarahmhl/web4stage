# Web4Stage

Application web de gestion de stages développée dans le cadre d’un projet académique CESI.

---

## Project Overview

Web4Stage est une application web qui permet de faciliter la recherche de stages en centralisant les offres, les informations des entreprises et le suivi des candidatures.

Elle répond aux besoins suivants :

* Centraliser les offres de stage sur une seule plateforme
* Stocker les informations des entreprises
* Aider les étudiants à suivre leurs candidatures
* Proposer des interfaces adaptées selon le rôle utilisateur

---

## Project Objectives

L’objectif principal est de proposer une plateforme simple et pratique permettant :

* Aux étudiants :

  * Rechercher des offres
  * Postuler
  * Suivre leurs candidatures
  * Gérer une wishlist

* Aux pilotes (encadrants pédagogiques) :

  * Suivre les candidatures des étudiants
  * Publier des offres

* Aux administrateurs :

  * Gérer les utilisateurs
  * Superviser les offres
  * Administrer la plateforme

---

## Features

* Authentification avec gestion des rôles
* Redirection automatique selon le rôle
* Gestion des offres (CRUD)
* Gestion des entreprises (CRUD)
* Suivi des candidatures
* Wishlist étudiant
* Tableaux de bord (étudiant / pilote / admin)
* Pagination des offres

---

## Technologies Used

* PHP
* MySQL
* Apache
* HTML / CSS / JavaScript
* Architecture MVC

---

## Project Requirements

* Architecture MVC
* Design responsive
* Système d’authentification sécurisé
* Bonnes pratiques de sécurité
* Optimisation SEO basique

---

## Local Setup

1. Placer le projet dans :
   C:\xampp\htdocs\projet_web

2. Démarrer Apache et MySQL via XAMPP

3. Créer la base de données :
   web4stage

4. Importer :

   * database/01_schema.sql
   * database/02_seed.sql

5. Accéder à l’application :
   http://localhost/projet%20web/public/index.php/

---

## Test Accounts

* Étudiant : [lea.martin@viacesi.fr](mailto:lea.martin@viacesi.fr)
* Pilote : [pilote-01@web4stage.local](mailto:pilote-01@web4stage.local)
* Admin : [admin-01@web4stage.local](mailto:admin-01@web4stage.local)

Mot de passe :
ChangeMe123!

---

## Project Context

Ce projet a été réalisé dans un cadre académique au CESI.
Il suit un cahier des charges visant à développer une application complète de gestion de stages.

---

## Authors

* Lyna
* Sarah
* Nouara
