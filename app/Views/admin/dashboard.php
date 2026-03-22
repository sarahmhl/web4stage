<?php // Vue du tableau de bord admin avec resume global, actions rapides et suivis. ?>
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
</header>

<section class="dashboard-grid">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue plateforme</span>
      <span class="pill-small">Back-office</span>
    </header>
    <ul class="list-compact">
      <li><span>Utilisateurs actifs</span><strong><?= (int) $stats['users'] ?></strong></li>
      <li><span>Offres publiées</span><strong><?= (int) $stats['offers'] ?></strong></li>
      <li><span>Entreprises référencées</span><strong><?= (int) $stats['companies'] ?></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">À traiter</span>
      <span class="pill-small">Retours</span>
    </header>
    <ul class="list-compact">
      <li><span>Actions en attente</span><strong><?= (int) $stats['pendingActions'] ?></strong></li>
      <li><span>Gestion des comptes</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
      <li><span>Modération</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Actions rapides</h2>
      <p class="section-subtitle">Les tâches les plus fréquentes dans l administration du site.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">Comptes</span>
      <h3 class="action-card-title">Gérer les comptes</h3>
      <p class="action-card-text">Consultez les profils étudiants, pilotes et administrateurs.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les comptes</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Modération</span>
      <h3 class="action-card-title">Contrôler les avis et retours</h3>
      <p class="action-card-text">Relisez les retours publiés sur la plateforme et sur les entreprises.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="btn btn-outline">Ouvrir la modération</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Modifier les offres de stage</h3>
      <p class="action-card-text">Mettez à jour les fiches d offres depuis l interface d administration.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier'), ENT_QUOTES) ?>" class="btn btn-outline">Modifier les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Qualité</span>
      <h3 class="action-card-title">Vérifier la qualité globale</h3>
      <p class="action-card-text">Gardez une vue synthétique sur les offres et les entreprises.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/qualite'), ENT_QUOTES) ?>" class="btn btn-outline">Voir la qualité</a>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Aperçu rapide</h2>
      <p class="section-subtitle">Quelques éléments récents pour piloter le site sans changer de page.</p>
    </div>
  </div>

  <div class="dashboard-grid">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Comptes récents</span>
        <span class="pill-small"><?= count($users ?? []) ?> profils</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($users ?? []) as $user): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $user['prenom'] . ' ' . (string) $user['nom']), ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $user['role'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Offres récentes</span>
        <span class="pill-small"><?= count($offers ?? []) ?> offres</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($offers ?? []) as $offer): ?>
          <li>
            <span><?= htmlspecialchars((string) $offer['titre'], ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $offer['entreprise_nom'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Avis étudiants</span>
        <span class="pill-small"><?= count($feedbacks ?? []) ?> retours</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($feedbacks ?? []) as $feedback): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
            <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Évaluations entreprises</span>
        <span class="pill-small"><?= count($companyReviews ?? []) ?> avis</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($companyReviews ?? []) as $review): ?>
          <li>
            <span><?= htmlspecialchars((string) $review['entreprise_nom'], ENT_QUOTES) ?></span>
            <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>
  </div>
</section>
