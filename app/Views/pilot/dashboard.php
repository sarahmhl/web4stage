<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord pilote</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($pilotName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Pilote</span>
    <p class="dashboard-subtitle">
      Suivez la promotion, l'activité des candidatures, les retours étudiants et les entreprises à relancer.
    </p>
  </div>
  <span class="pill-role">Rôle : Pilote</span>
</header>

<section class="dashboard-grid" aria-label="Résumé du pilotage">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue promotion</span>
      <span class="pill-small">Suivi pédagogique</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Étudiants suivis</span>
        <strong><?= (int) $stats['students'] ?></strong>
      </li>
      <li>
        <span>Candidatures actives</span>
        <strong><?= (int) $stats['applications'] ?></strong>
      </li>
      <li>
        <span>Entretiens identifiés</span>
        <strong><?= (int) $stats['interviews'] ?></strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Priorités</span>
      <span class="pill-small">À surveiller</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Entreprises partenaires actives</span>
        <strong><?= (int) $stats['companies'] ?></strong>
      </li>
      <li>
        <span>Étudiants sans entretien</span>
        <strong>9</strong>
      </li>
      <li>
        <span>Dossiers à relancer</span>
        <strong>6</strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Retours formation</span>
      <span class="pill-small">Nouveaux avis</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Avis étudiants reçus</span>
        <strong>14</strong>
      </li>
      <li>
        <span>Retour moyen accompagnement</span>
        <strong>4,4 / 5</strong>
      </li>
      <li>
        <span>Points à améliorer</span>
        <strong>CV, entretiens</strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Rendez-vous promo</span>
      <span class="pill-small">Semaine en cours</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Points individuels prévus</span>
        <strong>7</strong>
      </li>
      <li>
        <span>Étudiants à convoquer</span>
        <strong>4</strong>
      </li>
      <li>
        <span>Relances entreprises</span>
        <strong>3</strong>
      </li>
    </ul>
  </article>
</section>

<section class="section" aria-labelledby="section-actions-pilote">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-actions-pilote">Actions rapides</h2>
      <p class="section-subtitle">
        Les raccourcis les plus utiles pour accompagner la promotion.
      </p>
    </div>
  </div>

  <div class="action-grid" aria-label="Actions rapides pilote">
    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Ajouter une offre de stage</h3>
      <p class="action-card-text">
        Creez une nouvelle offre avec son entreprise, ses competences, sa duree et son visuel.
      </p>
      <a href="pilote/offres/ajouter" class="btn btn-outline">Ajouter une offre</a>
    </article>
    <article class="action-card">
      <span class="pill-small">Avis</span>
      <h3 class="action-card-title">Consulter les avis sur la formation</h3>
      <p class="action-card-text">
        Visualisez ce que les étudiants disent de l'accompagnement et des outils de recherche de stage.
      </p>
      <a href="#" class="btn btn-outline">Voir les retours</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Suivi</span>
      <h3 class="action-card-title">Relancer les étudiants en retard</h3>
      <p class="action-card-text">
        Identifiez les profils peu actifs ou sans candidature récente.
      </p>
      <a href="#" class="btn btn-outline">Préparer les relances</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Entreprises</span>
      <h3 class="action-card-title">Contacter les entreprises partenaires</h3>
      <p class="action-card-text">
        Gérez les relances, les opportunités et les retours des structures d'accueil.
      </p>
      <a href="#" class="btn btn-outline">Ouvrir le suivi</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-suivi-pilote">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-suivi-pilote">Suivi des candidatures</h2>
      <p class="section-subtitle">
        Vue synthétique des offres suivies par votre promotion.
      </p>
    </div>
  </div>

  <section class="page-layout">
    <aside class="side-card">
      <h3 class="side-card-title">Points d'attention</h3>
      <p class="side-card-text">
        Identifiez rapidement les étudiants à accompagner et les entreprises à relancer.
      </p>
      <div class="stat-row">
        <div class="stat-pill">
          <span>Étudiants en attente</span>
          <strong>12</strong>
        </div>
        <div class="stat-pill">
          <span>Entretiens planifiés</span>
          <strong>11</strong>
        </div>
        <div class="stat-pill">
          <span>Stages confirmés</span>
          <strong>7</strong>
        </div>
      </div>
    </aside>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Dernières candidatures suivies</span>
        <span class="pill-small">Promo Bachelor 3</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Emma L. · Tech Studio · Développeur Web</span>
          <span class="badge-status badge-status--pending">En attente</span>
        </li>
        <li>
          <span>Nassim T. · DataWorks · Analyste BI</span>
          <span class="badge-status badge-status--accepted">Entretien</span>
        </li>
        <li>
          <span>Camille M. · Growth Media · Marketing digital</span>
          <span class="badge-status badge-status--pending">À relancer</span>
        </li>
      </ul>
    </article>
  </section>
</section>

<section class="section" aria-labelledby="section-pilot-retours">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-pilot-retours">Retours et qualité</h2>
      <p class="section-subtitle">
        Les éléments de terrain qui aident à ajuster l'accompagnement de la promotion.
      </p>
    </div>
  </div>

  <div class="dashboard-grid" aria-label="Retours et qualité pilote">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Avis étudiants récents</span>
        <span class="pill-small">Formation</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Atelier CV apprécié</span>
          <strong>9 mentions</strong>
        </li>
        <li>
          <span>Besoin d'entraînements entretien</span>
          <strong>6 mentions</strong>
        </li>
        <li>
          <span>Demandes de suivi individualisé</span>
          <strong>4 mentions</strong>
        </li>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Entreprises à suivre</span>
        <span class="pill-small">Relations</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Tech Studio · retour sur profils</span>
          <span class="badge-status badge-status--pending">En attente</span>
        </li>
        <li>
          <span>Growth Media · nouvelle offre probable</span>
          <span class="badge-status badge-status--accepted">À confirmer</span>
        </li>
        <li>
          <span>DataWorks · bilan fin de stage demandé</span>
          <span class="badge-status">Prévu</span>
        </li>
      </ul>
    </article>
  </div>
</section>
