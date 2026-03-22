<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord administrateur</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($adminName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Admin</span>
    <p class="dashboard-subtitle">
      Administrez les comptes, les offres, les retours utilisateurs et la supervision globale de la plateforme.
    </p>
  </div>
  <span class="pill-role">Rôle : Administrateur</span>
</header>

<section class="dashboard-grid" aria-label="Résumé de l'administration">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue plateforme</span>
      <span class="pill-small">Back-office</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Utilisateurs actifs</span>
        <strong><?= (int) $stats['users'] ?></strong>
      </li>
      <li>
        <span>Offres publiées</span>
        <strong><?= (int) $stats['offers'] ?></strong>
      </li>
      <li>
        <span>Entreprises enregistrées</span>
        <strong><?= (int) $stats['companies'] ?></strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Actions à traiter</span>
      <span class="pill-small">Aujourd'hui</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Demandes en attente</span>
        <strong><?= (int) $stats['pendingActions'] ?></strong>
      </li>
      <li>
        <span>Comptes pilotes à vérifier</span>
        <strong>2</strong>
      </li>
      <li>
        <span>Offres à archiver</span>
        <strong>4</strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Qualité de service</span>
      <span class="pill-small">Retours</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Avis utilisateurs non lus</span>
        <strong>11</strong>
      </li>
      <li>
        <span>Note moyenne plateforme</span>
        <strong>4,3 / 5</strong>
      </li>
      <li>
        <span>Signalements ouverts</span>
        <strong>3</strong>
      </li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Contrôles techniques</span>
      <span class="pill-small">Sécurité</span>
    </header>
    <ul class="list-compact">
      <li>
        <span>Dernière sauvegarde</span>
        <strong>02h15</strong>
      </li>
      <li>
        <span>Comptes bloqués</span>
        <strong>1</strong>
      </li>
      <li>
        <span>Alertes à vérifier</span>
        <strong>2</strong>
      </li>
    </ul>
  </article>
</section>

<section class="section" aria-labelledby="section-actions-admin">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-actions-admin">Actions rapides</h2>
      <p class="section-subtitle">
        Les tâches de pilotage les plus fréquentes dans l'administration du site.
      </p>
    </div>
  </div>

  <div class="action-grid" aria-label="Actions rapides administrateur">
    <article class="action-card">
      <span class="pill-small">Comptes</span>
      <h3 class="action-card-title">Valider les accès utilisateurs</h3>
      <p class="action-card-text">
        Créez, modifiez ou désactivez les comptes étudiants, pilotes et administrateurs.
      </p>
      <a href="#" class="btn btn-outline">Gérer les comptes</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Modération</span>
      <h3 class="action-card-title">Contrôler les avis et retours</h3>
      <p class="action-card-text">
        Vérifiez les signalements et la qualité des retours publiés sur la plateforme.
      </p>
      <a href="#" class="btn btn-outline">Ouvrir la modération</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Modifier les offres de stage</h3>
      <p class="action-card-text">
        Ouvrez une offre existante, mettez à jour son contenu puis enregistrez les changements.
      </p>
      <a href="admin/offres/modifier" class="btn btn-outline">Modifier les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Conformité</span>
      <h3 class="action-card-title">Vérifier la qualité globale</h3>
      <p class="action-card-text">
        Contrôlez les comptes inactifs, les données sensibles et les éléments à sécuriser.
      </p>
      <a href="#" class="btn btn-outline">Lancer le contrôle</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-admin-outils">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-admin-outils">Supervision globale</h2>
      <p class="section-subtitle">
        Contrôlez les accès et gardez une vue d'ensemble sur le fonctionnement du site.
      </p>
    </div>
  </div>

  <section class="page-layout">
    <aside class="side-card">
      <h3 class="side-card-title">Répartition des comptes</h3>
      <p class="side-card-text">
        Contrôle rapide des rôles et de l'activité administrative.
      </p>
      <div class="stat-row">
        <div class="stat-pill">
          <span>Étudiants</span>
          <strong>112</strong>
        </div>
        <div class="stat-pill">
          <span>Pilotes</span>
          <strong>8</strong>
        </div>
        <div class="stat-pill">
          <span>Admins</span>
          <strong>6</strong>
        </div>
      </div>
    </aside>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Journal des actions récentes</span>
        <span class="pill-small">Dernières opérations</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Validation d'un compte pilote · Marie D.</span>
          <span class="badge-status badge-status--accepted">Validé</span>
        </li>
        <li>
          <span>Archivage d'une offre expirée · Tech Studio</span>
          <span class="badge-status">Archivé</span>
        </li>
        <li>
          <span>Contrôle d'un signalement entreprise</span>
          <span class="badge-status badge-status--pending">En cours</span>
        </li>
      </ul>
    </article>
  </section>
</section>

<section class="section" aria-labelledby="section-admin-qualite">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-admin-qualite">Qualité et maintenance</h2>
      <p class="section-subtitle">
        Les indicateurs qui permettent de garder la plateforme stable et utile pour tous.
      </p>
    </div>
  </div>

  <div class="dashboard-grid" aria-label="Qualité et maintenance administrateur">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Retours utilisateurs</span>
        <span class="pill-small">Feedback</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Demandes d'amélioration UX</span>
          <strong>5</strong>
        </li>
        <li>
          <span>Avis sur la formation à transmettre</span>
          <strong>8</strong>
        </li>
        <li>
          <span>Suggestions sur les tableaux de bord</span>
          <strong>3</strong>
        </li>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Maintenance préventive</span>
        <span class="pill-small">Technique</span>
      </header>
      <ul class="list-compact">
        <li>
          <span>Nettoyage des comptes inactifs</span>
          <span class="badge-status badge-status--pending">Planifié</span>
        </li>
        <li>
          <span>Vérification des permissions</span>
          <span class="badge-status badge-status--accepted">OK</span>
        </li>
        <li>
          <span>Contrôle des données seed/demo</span>
          <span class="badge-status">À revoir</span>
        </li>
      </ul>
    </article>
  </div>
</section>
