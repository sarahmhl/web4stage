<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord administrateur</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($adminName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Admin</span>
    <p class="dashboard-subtitle">
      Administrez les comptes, les offres, les entreprises, les retours utilisateurs et la supervision globale de la plateforme.
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
      <li><span>Offres publiees</span><strong><?= (int) $stats['offers'] ?></strong></li>
      <li><span>Entreprises referencees</span><strong><?= (int) $stats['companies'] ?></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">A traiter</span>
      <span class="pill-small">Retours</span>
    </header>
    <ul class="list-compact">
      <li><span>Actions en attente</span><strong><?= (int) $stats['pendingActions'] ?></strong></li>
      <li><span>Gestion des comptes</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
      <li><span>Moderation</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Actions rapides</h2>
      <p class="section-subtitle">Les taches les plus frequentes dans l administration du site.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">Comptes</span>
      <h3 class="action-card-title">Gerer les comptes</h3>
      <p class="action-card-text">Creez, modifiez ou supprimez les profils etudiants, pilotes et administrateurs.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" class="btn btn-outline">Gerer les comptes</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Entreprises</span>
      <h3 class="action-card-title">Gerer les entreprises</h3>
      <p class="action-card-text">Maintenez les partenaires, contacts et fiches entreprise a jour.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Gerer les entreprises</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Moderation</span>
      <h3 class="action-card-title">Controler les avis et retours</h3>
      <p class="action-card-text">Relisez les retours publies sur la plateforme et sur les entreprises.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="btn btn-outline">Ouvrir la moderation</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Modifier les offres de stage</h3>
      <p class="action-card-text">Mettez a jour ou supprimez les fiches d offres depuis l interface d administration.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier'), ENT_QUOTES) ?>" class="btn btn-outline">Modifier les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Qualite</span>
      <h3 class="action-card-title">Verifier la qualite globale</h3>
      <p class="action-card-text">Gardez une vue synthetique sur les offres et les entreprises.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/qualite'), ENT_QUOTES) ?>" class="btn btn-outline">Voir la qualite</a>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Apercu rapide</h2>
      <p class="section-subtitle">Quelques elements recents pour piloter le site sans changer de page.</p>
    </div>
  </div>

  <div class="dashboard-grid">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Comptes recents</span>
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
        <span class="dash-card-title">Offres recentes</span>
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
        <span class="dash-card-title">Avis etudiants</span>
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
        <span class="dash-card-title">Evaluations entreprises</span>
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
