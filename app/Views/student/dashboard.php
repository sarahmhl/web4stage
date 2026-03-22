<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord etudiant</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($studentName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Etudiant</span>
    <p class="dashboard-subtitle">
      Suivez vos offres, vos candidatures, vos documents et vos retours directement depuis votre espace.
    </p>
  </div>
  <span class="pill-role">Rôle : Étudiant</span>
</header>

<section class="dashboard-grid" aria-label="Résumé de votre activité">
  <article class="dash-card" aria-label="Résumé de la recherche">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue d'ensemble</span>
      <span class="pill-small">Objectif : 1 stage validé</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Offres en favoris</span>
        <strong><?= (int) $stats['wishlist'] ?></strong>
      </li>
      <li>
        <span>Candidatures envoyées</span>
        <strong><?= (int) $stats['applications'] ?></strong>
      </li>
      <li>
        <span>Entretiens prévus</span>
        <strong><?= (int) $stats['interviews'] ?></strong>
      </li>
    </ul>
  </article>

  <article class="dash-card" aria-label="Avancement de la recherche">
    <header class="dash-card-header">
      <span class="dash-card-title">Avancement</span>
      <span class="pill-small">Recommandé : 8 à 10 candidatures</span>
    </header>
    <div class="hero-progress">
      <div class="hero-progress-bar">
        <?php
          $target = 8;
          $current = (int) $stats['applications'];
          $percent = max(0, min(100, (int) round($current / $target * 100)));
        ?>
        <div class="hero-progress-fill" style="width: <?= $percent ?>%"></div>
      </div>
      <span class="hero-progress-label">
        <?= (int) $stats['applications'] ?> candidatures sur <?= $target ?>
      </span>
    </div>
  </article>

  <article class="dash-card" aria-label="Checklist du moment">
    <header class="dash-card-header">
      <span class="dash-card-title">Checklist du moment</span>
      <span class="pill-small">À faire</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Mettre à jour le CV</span>
        <span class="badge-status badge-status--accepted">Prêt</span>
      </li>
      <li>
        <span>Finaliser la lettre de motivation</span>
        <span class="badge-status badge-status--pending">À revoir</span>
      </li>
      <li>
        <span>Compléter le profil étudiant</span>
        <span class="badge-status">85%</span>
      </li>
    </ul>
  </article>

  <article class="dash-card" aria-label="Mon dossier candidat">
    <header class="dash-card-header">
      <span class="dash-card-title">Mon dossier candidat</span>
      <span class="pill-small">Documents</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>CV principal</span>
        <strong>Version mars 2026</strong>
      </li>
      <li>
        <span>Lettre type</span>
        <strong>2 modèles</strong>
      </li>
      <li>
        <span>Portfolio / GitHub</span>
        <strong>Ajouté</strong>
      </li>
    </ul>
  </article>
</section>

<section class="section" aria-labelledby="section-actions-etudiant">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-actions-etudiant">Actions rapides</h2>
      <p class="section-subtitle">
        Les raccourcis utiles pour continuer votre recherche sans perdre de temps.
      </p>
    </div>
  </div>

  <div class="action-grid" aria-label="Actions rapides étudiant">
    <article class="action-card">
      <span class="pill-small">Retour</span>
      <h3 class="action-card-title">Donner son avis sur la formation</h3>
      <p class="action-card-text">
        Partagez votre ressenti sur l'accompagnement, les ateliers et la préparation au stage.
      </p>
      <a href="#" class="btn btn-outline">Donner mon avis</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Évaluation</span>
      <h3 class="action-card-title">Évaluer une entreprise</h3>
      <p class="action-card-text">
        Laissez une appréciation après entretien ou après stage pour aider les autres étudiants.
      </p>
      <a href="#" class="btn btn-outline">Ajouter un avis</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Documents</span>
      <h3 class="action-card-title">Mettre à jour CV et LM</h3>
      <p class="action-card-text">
        Gardez un dossier propre et prêt à envoyer pour vos prochaines candidatures.
      </p>
      <a href="#" class="btn btn-outline">Mettre à jour</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-wishlist">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-wishlist">Offres en favoris</h2>
      <p class="section-subtitle">
        Offres enregistrées pour suivi et candidature.
      </p>
    </div>
    <div class="section-actions">
      <a href="offres" class="link-soft">Ajouter des offres -></a>
    </div>
  </div>

  <div class="offers-grid" aria-label="Offres en favoris">
    <article class="offer-card">
      <header class="offer-card-header">
        <div class="offer-badge">PT</div>
        <div>
          <h3 class="offer-title">Stage Développeur Web Fullstack</h3>
          <p class="offer-company">Tech Studio · Paris</p>
        </div>
      </header>
      <div class="offer-meta">
        <span>6 mois</span>
        <span>1 000 EUR/mois</span>
        <span>Début : Avril</span>
      </div>
      <div class="offer-skills">
        <span class="tag">PHP / MVC</span>
        <span class="tag">JavaScript</span>
        <span class="tag">Qualité logicielle</span>
      </div>
      <footer class="offer-footer">
        <div class="offer-tagline">Ajoutée il y a 3 jours.</div>
        <div class="offer-actions">
          <button type="button" class="btn-icon btn-icon--wish active" aria-label="Retirer des favoris">♥</button>
          <a href="#" class="btn btn-outline" style="padding-inline: 0.9rem">Détails</a>
        </div>
      </footer>
    </article>

    <article class="offer-card">
      <header class="offer-card-header">
        <div class="offer-badge">DW</div>
        <div>
          <h3 class="offer-title">Stage Data Analyst Junior</h3>
          <p class="offer-company">DataWorks · Nantes</p>
        </div>
      </header>
      <div class="offer-meta">
        <span>5 mois</span>
        <span>900 EUR/mois</span>
        <span>Début : Mai</span>
      </div>
      <div class="offer-skills">
        <span class="tag">SQL</span>
        <span class="tag">Power BI</span>
        <span class="tag">Analyse</span>
      </div>
      <footer class="offer-footer">
        <div class="offer-tagline">Entreprise très consultée cette semaine.</div>
        <div class="offer-actions">
          <button type="button" class="btn-icon btn-icon--wish active" aria-label="Retirer des favoris">♥</button>
          <a href="#" class="btn btn-outline" style="padding-inline: 0.9rem">Détails</a>
        </div>
      </footer>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-candidatures">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-candidatures">Candidatures</h2>
      <p class="section-subtitle">
        Suivez l'état de vos candidatures et les prochaines actions.
      </p>
    </div>
  </div>

  <section class="page-layout" aria-label="Candidatures et détails">
    <aside class="side-card">
      <h3 class="side-card-title">Résumé rapide</h3>
      <p class="side-card-text">
        Chaque candidature apparaît ici avec son statut de traitement.
      </p>
      <div class="stat-row">
        <div class="stat-pill">
          <span>En attente</span>
          <strong><?= (int) $stats['pending'] ?></strong>
        </div>
        <div class="stat-pill">
          <span>Entretiens / validées</span>
          <strong><?= (int) $stats['accepted'] ?></strong>
        </div>
        <div class="stat-pill">
          <span>Refusées</span>
          <strong><?= (int) $stats['rejected'] ?></strong>
        </div>
      </div>
    </aside>

    <article class="dash-card" aria-label="Liste détaillée des candidatures">
      <header class="dash-card-header">
        <span class="dash-card-title">Détail des candidatures</span>
        <span class="pill-small">CV et LM associés</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Stage Dev Web PHP / JS · BubbleTech</span>
          <span class="badge-status badge-status--pending">En attente</span>
        </li>
        <li>
          <span>Stage UX / UI Designer · Studio Interfaces</span>
          <span class="badge-status badge-status--accepted">Entretien prévu</span>
        </li>
        <li>
          <span>Stage Data &amp; BI · DataWorks</span>
          <span class="badge-status badge-status--pending">En cours d'étude</span>
        </li>
      </ul>
    </article>
  </section>
</section>

<section class="section" aria-labelledby="section-suivi-etudiant">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-suivi-etudiant">Suivi personnel</h2>
      <p class="section-subtitle">
        Les retours et éléments utiles pour rester organisé pendant la recherche.
      </p>
    </div>
  </div>

  <div class="dashboard-grid" aria-label="Suivi personnel étudiant">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Avis et évaluations</span>
        <span class="pill-small">À compléter</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Avis sur la formation</span>
          <span class="badge-status badge-status--pending">À remplir</span>
        </li>
        <li>
          <span>Évaluation entreprise après entretien</span>
          <span class="badge-status">Disponible</span>
        </li>
        <li>
          <span>Bilan de stage final</span>
          <span class="badge-status badge-status--pending">Plus tard</span>
        </li>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Agenda de la semaine</span>
        <span class="pill-small">Planning</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Lundi · Relecture LM DataWorks</span>
          <strong>18h00</strong>
        </li>
        <li>
          <span>Mercredi · Point avec le pilote</span>
          <strong>10h30</strong>
        </li>
        <li>
          <span>Vendredi · Entretien Studio Interfaces</span>
          <strong>14h00</strong>
        </li>
      </ul>
    </article>
  </div>
</section>
