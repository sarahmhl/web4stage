<?php
$stats = is_array($stats ?? null) ? $stats : [];
$insights = is_array($insights ?? null) ? $insights : [];
$users = is_array($users ?? null) ? $users : [];
$offers = is_array($offers ?? null) ? $offers : [];
$companies = is_array($companies ?? null) ? $companies : [];
$feedbacks = is_array($feedbacks ?? null) ? $feedbacks : [];
$companyReviews = is_array($companyReviews ?? null) ? $companyReviews : [];
?>
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

<section class="dashboard-lead" aria-label="Résumé administrateur">
  <article class="dash-card dashboard-lead-card">
    <div class="dashboard-lead-head">
      <div class="dashboard-lead-copy">
        <span class="pill-small">Vision plateforme</span>
        <h2 class="dashboard-lead-title">Vue d’ensemble de la plateforme</h2>
        <p class="dashboard-lead-text">Les volumes essentiels du back-office sont résumés ici.</p>
      </div>

      <div class="dashboard-lead-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="btn btn-primary">Ouvrir la modération</a>
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/qualite'), ENT_QUOTES) ?>" class="btn btn-outline">Voir la qualité</a>
      </div>
    </div>

    <div class="dashboard-lead-stats">
      <div class="dashboard-lead-stat">
        <span>Utilisateurs</span>
        <strong><?= (int) ($stats['users'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Offres</span>
        <strong><?= (int) ($stats['offers'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Retours visibles</span>
        <strong><?= (int) ($stats['pendingActions'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Entreprises sans offre</span>
        <strong><?= (int) ($insights['companiesWithoutOffers'] ?? 0) ?></strong>
      </div>
    </div>
  </article>
</section>

<section class="dashboard-grid dashboard-grid--summary">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Utilisateurs</span>
      <span class="pill-small"><?= (int) ($stats['users'] ?? 0) ?> profils</span>
    </header>
    <ul class="list-compact">
      <li><span>Étudiants</span><strong><?= (int) ($insights['students'] ?? 0) ?></strong></li>
      <li><span>Pilotes</span><strong><?= (int) ($insights['pilots'] ?? 0) ?></strong></li>
      <li><span>Admins</span><strong><?= (int) ($insights['admins'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">La répartition des rôles est visible d’un coup d’œil pour éviter les oublis de couverture.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Catalogue</span>
      <span class="pill-small"><?= (int) ($stats['offers'] ?? 0) ?> offres</span>
    </header>
    <ul class="list-compact">
      <li><span>Entreprises référencées</span><strong><?= (int) ($stats['companies'] ?? 0) ?></strong></li>
      <li><span>Sans offre</span><strong><?= (int) ($insights['companiesWithoutOffers'] ?? 0) ?></strong></li>
      <li><span>Sans note</span><strong><?= (int) ($insights['unratedCompanies'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Ce bloc aide à repérer les fiches entreprises sous-exploitées ou encore trop vides.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Modération</span>
      <span class="pill-small"><?= (int) ($stats['pendingActions'] ?? 0) ?> retours</span>
    </header>
    <ul class="list-compact">
      <li><span>Avis étudiants</span><strong><?= count($feedbacks) ?></strong></li>
      <li><span>Avis entreprises</span><strong><?= count($companyReviews) ?></strong></li>
      <li><span>Actions en attente</span><strong><?= (int) ($stats['pendingActions'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Les contenus publiés récents restent accessibles sans passer par une page vide ou trop technique.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Qualité</span>
      <span class="pill-small">Supervision</span>
    </header>
    <ul class="list-compact">
      <li><span>Note étudiants</span><strong><?= ($insights['studentFeedbackAverage'] ?? null) !== null ? htmlspecialchars((string) $insights['studentFeedbackAverage'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
      <li><span>Note entreprises</span><strong><?= ($insights['companyReviewAverage'] ?? null) !== null ? htmlspecialchars((string) $insights['companyReviewAverage'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
      <li><span>Entreprises fragiles</span><strong><?= (int) ($insights['lowRatedCompanies'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Les alertes qualité permettent de prioriser les nettoyages de données et les contrôles.</p>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Actions rapides</h2>
      <p class="section-subtitle">Les tâches les plus fréquentes dans l’administration du site.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">Comptes</span>
      <h3 class="action-card-title">Gérer les comptes</h3>
      <p class="action-card-text">Créez, modifiez ou supprimez les profils étudiants, pilotes et administrateurs.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les comptes</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Entreprises</span>
      <h3 class="action-card-title">Gérer les entreprises</h3>
      <p class="action-card-text">Maintenez les partenaires, contacts et fiches entreprise à jour.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les entreprises</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Gérer les offres de stage</h3>
      <p class="action-card-text">Créez, mettez à jour ou supprimez les fiches d’offres depuis l’interface d’administration.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Modération</span>
      <h3 class="action-card-title">Contrôler les avis et retours</h3>
      <p class="action-card-text">Relisez les retours publiés sur la plateforme et sur les entreprises.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="btn btn-outline">Ouvrir la modération</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Qualité</span>
      <h3 class="action-card-title">Vérifier la qualité globale</h3>
      <p class="action-card-text">Gardez une vue synthétique sur les offres, les entreprises et la satisfaction générale.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/qualite'), ENT_QUOTES) ?>" class="btn btn-outline">Voir la qualité</a>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Pilotage global</h2>
      <p class="section-subtitle">Trois angles pour savoir où intervenir en priorité.</p>
    </div>
  </div>

  <div class="dashboard-grid dashboard-grid--three">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Répartition des rôles</span>
        <span class="pill-small">Couverture</span>
      </header>
      <ul class="list-compact">
        <li><span>Étudiants par pilote</span><strong><?= (int) ($insights['pilots'] ?? 0) > 0 ? number_format((int) ($insights['students'] ?? 0) / max(1, (int) ($insights['pilots'] ?? 1)), 1, ',', ' ') : 'N/A' ?></strong></li>
        <li><span>Profils admin</span><strong><?= (int) ($insights['admins'] ?? 0) ?></strong></li>
        <li><span>Population totale</span><strong><?= (int) ($stats['users'] ?? 0) ?></strong></li>
      </ul>
      <div class="dashboard-chip-row">
        <span class="dashboard-chip">Comptes</span>
        <span class="dashboard-chip">Rôles</span>
        <span class="dashboard-chip">Accès</span>
      </div>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Santé du catalogue</span>
        <span class="pill-small">Contenu</span>
      </header>
      <ul class="list-compact">
        <li><span>Entreprises sans offre</span><strong><?= (int) ($insights['companiesWithoutOffers'] ?? 0) ?></strong></li>
        <li><span>Entreprises sans note</span><strong><?= (int) ($insights['unratedCompanies'] ?? 0) ?></strong></li>
        <li><span>Entreprises à surveiller</span><strong><?= (int) ($insights['lowRatedCompanies'] ?? 0) ?></strong></li>
      </ul>
      <div class="dashboard-chip-row">
        <span class="dashboard-chip">Catalogue</span>
        <span class="dashboard-chip">Offres</span>
        <span class="dashboard-chip">Partenaires</span>
      </div>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Météo des retours</span>
        <span class="pill-small">Qualité perçue</span>
      </header>
      <ul class="list-compact">
        <li><span>Moyenne étudiants</span><strong><?= ($insights['studentFeedbackAverage'] ?? null) !== null ? htmlspecialchars((string) $insights['studentFeedbackAverage'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
        <li><span>Moyenne entreprises</span><strong><?= ($insights['companyReviewAverage'] ?? null) !== null ? htmlspecialchars((string) $insights['companyReviewAverage'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
        <li><span>Total retours</span><strong><?= (int) ($stats['pendingActions'] ?? 0) ?></strong></li>
      </ul>
      <div class="dashboard-chip-row">
        <span class="dashboard-chip">Feedback</span>
        <span class="dashboard-chip">Avis</span>
        <span class="dashboard-chip">Modération</span>
      </div>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Aperçus utiles</h2>
      <p class="section-subtitle">Des listes plus chargées pour agir tout de suite depuis le tableau de bord.</p>
    </div>
  </div>

  <div class="dashboard-grid dashboard-grid--three">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Comptes visibles</span>
        <span class="pill-small"><?= count($users) ?> profils</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($users as $user): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $user['prenom'] . ' ' . (string) $user['nom']), ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $user['role'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" class="link-soft">Ouvrir la gestion des comptes -&gt;</a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Entreprises visibles</span>
        <span class="pill-small"><?= count($companies) ?> structures</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($companies as $company): ?>
          <li>
            <span><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
            <strong><?= (int) ($company['offers_count'] ?? 0) ?> offre(s)</strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises'), ENT_QUOTES) ?>" class="link-soft">Ouvrir la gestion des entreprises -&gt;</a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Offres visibles</span>
        <span class="pill-small"><?= count($offers) ?> offres</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($offers as $offer): ?>
          <li>
            <span><?= htmlspecialchars((string) $offer['titre'], ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $offer['entreprise_nom'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier'), ENT_QUOTES) ?>" class="link-soft">Ouvrir la gestion des offres -&gt;</a>
      </p>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Retours récents</h2>
      <p class="section-subtitle">Les derniers signaux de satisfaction affichés directement dans l’espace admin.</p>
    </div>
  </div>

  <div class="dashboard-grid">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Avis étudiants</span>
        <span class="pill-small"><?= count($feedbacks) ?> retours</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($feedbacks as $feedback): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
            <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="link-soft">Voir tous les avis étudiants -&gt;</a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Évaluations entreprises</span>
        <span class="pill-small"><?= count($companyReviews) ?> avis</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($companyReviews as $review): ?>
          <li>
            <span><?= htmlspecialchars((string) $review['entreprise_nom'], ENT_QUOTES) ?></span>
            <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('admin/moderation'), ENT_QUOTES) ?>" class="link-soft">Voir toutes les évaluations -&gt;</a>
      </p>
    </article>
  </div>
</section>
