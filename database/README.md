# Base de donnees Web4Stage

Ordre d'execution dans phpMyAdmin:
1. Creer la base `web4stage` (utf8mb4_unicode_ci)
2. Importer `database/01_schema.sql`
3. Importer `database/02_seed.sql`

Notes:
- Les comptes de seed utilisent le mot de passe: `ChangeMe123!`
- L'inscription publique reste reservee aux etudiants `@viacesi.fr` via le site.
- Formats de connexion attendus:
  - Etudiant: `lea.martin@viacesi.fr` ou `prenom.nom@viacesi.fr`
  - Pilote: `pilote-01@web4stage.local`
  - Admin: `admin-01@web4stage.local`
- Les images d'offres doivent etre dans `assets/img/offers/`.
