<?php
$stats = is_array($stats ?? null) ? $stats : [];
$applications = is_array($applications ?? null) ? $applications : [];
$documents = is_array($documents ?? null) ? $documents : [];
$featuredCompanies = is_array($featuredCompanies ?? null) ? $featuredCompanies : [];

$hasCv = !empty($documents['cv_path']);
$hasLetter = trim((string) ($documents['lettre_type'] ?? '')) !== '';
$readinessCount = ($hasCv ? 1 : 0) + ($hasLetter ? 1 : 0);
$latestApplication = $applications[0] ?? null;
$priorityLabel = 'Explorer les offres';
$priorityText = 'Commencez par cibler quelques offres adaptées à votre profil.';
$priorityRoute = 'offres';
$priorityAction = 'Voir les offres';

if (!$hasCv) {
    $priorityLabel = 'Compléter le CV';
    $priorityText = 'Votre CV n’est pas encore renseigné. C’est la première étape pour candidater efficacement.';
    $priorityRoute = 'etudiant/documents';
    $priorityAction = 'Ajouter mon CV';
} elseif (!$hasLetter) {
    $priorityLabel = 'Ajouter une lettre type';
    $priorityText = 'Une lettre type à jour vous fera gagner du temps pour vos prochaines candidatures.';
    $priorityRoute = 'etudiant/documents';
    $priorityAction = 'Compléter mes documents';
} elseif ((int) ($stats['wishlist'] ?? 0) === 0) {
    $priorityLabel = 'Construire une wish-list';
    $priorityText = 'Enregistrez quelques offres pour comparer vos pistes et organiser votre recherche.';
    $priorityRoute = 'offres';
    $priorityAction = 'Chercher des offres';
} elseif ((int) ($stats['applications'] ?? 0) === 0) {
    $priorityLabel = 'Envoyer une première candidature';
    $priorityText = 'Votre dossier est prêt. L’étape suivante consiste à envoyer vos premières candidatures.';
    $priorityRoute = 'wishlist';
    $priorityAction = 'Ouvrir ma wish-list';
} elseif ((int) ($stats['pending'] ?? 0) > 0) {
    $priorityLabel = 'Suivre les dossiers en attente';
    $priorityText = 'Certaines candidatures sont encore en cours. Suivez-les pour savoir quand relancer.';
    $priorityRoute = 'candidatures';
    $priorityAction = 'Voir mes candidatures';
}
?>
<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord étudiant</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars((string) $studentName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Étudiant</span>
    <p class="dashboard-subtitle">
      Suivez vos offres, vos candidatures, vos documents et vos prochaines actions depuis un espace clair et structuré.
    </p>
  </div>
</header>

<section class="dashboard-lead" aria-label="Résumé étudiant">
  <article class="dash-card dashboard-lead-card">
    <div class="dashboard-lead-head">
      <div class="dashboard-lead-copy">
        <span class="pill-small">Parcours étudiant</span>
        <h2 class="dashboard-lead-title">Vue d’ensemble de votre recherche</h2>
        <p class="dashboard-lead-text">Vos priorités, votre dossier et vos pistes récentes sont réunis ici.</p>
      </div>

      <div class="dashboard-lead-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route($priorityRoute), ENT_QUOTES) ?>" class="btn btn-primary"><?= htmlspecialchars($priorityAction, ENT_QUOTES) ?></a>
        <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-outline">Explorer les offres</a>
      </div>
    </div>

    <div class="dashboard-lead-stats">
      <div class="dashboard-lead-stat">
        <span>Candidatures</span>
        <strong><?= (int) ($stats['applications'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>&#9829; Wish-list</span>
        <strong><?= (int) ($stats['wishlist'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Dossier prêt</span>
        <strong><?= $readinessCount ?>/2</strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>En attente</span>
        <strong><?= (int) ($stats['pending'] ?? 0) ?></strong>
      </div>
    </div>
  </article>
</section>

<section class="dashboard-grid dashboard-grid--summary" aria-label="Résumé de votre activité">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Recherche</span>
      <span class="pill-small">En cours</span>
    </header>
    <ul class="list-compact">
      <li><span>&#9829; Offres en favoris</span><strong><?= (int) ($stats['wishlist'] ?? 0) ?></strong></li>
      <li><span>Candidatures envoyées</span><strong><?= (int) ($stats['applications'] ?? 0) ?></strong></li>
      <li><span>Entretiens prévus</span><strong><?= (int) ($stats['interviews'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Cette synthèse vous donne une vue rapide de l’avancement global de votre recherche de stage.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Statuts</span>
      <span class="pill-small">Suivi</span>
    </header>
    <ul class="list-compact">
      <li><span>En attente</span><strong><?= (int) ($stats['pending'] ?? 0) ?></strong></li>
      <li><span>Acceptées</span><strong><?= (int) ($stats['accepted'] ?? 0) ?></strong></li>
      <li><span>Refusées</span><strong><?= (int) ($stats['rejected'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Vous identifiez rapidement si votre recherche avance ou si vous devez diversifier vos candidatures.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Dossier candidat</span>
      <span class="pill-small"><?= $readinessCount ?>/2 prêt</span>
    </header>
    <ul class="list-compact">
      <li><span>CV</span><strong><?= $hasCv ? 'Prêt' : 'À ajouter' ?></strong></li>
      <li><span>Lettre type</span><strong><?= $hasLetter ? 'Prête' : 'À compléter' ?></strong></li>
      <li><span>Accès direct</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
    <p class="dashboard-card-note">Cette vue vous évite de découvrir au dernier moment qu’un document manque pour postuler.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Priorité</span>
      <span class="pill-small">Prochain pas</span>
    </header>
    <ul class="list-compact">
      <li><span>Focus du moment</span><strong><?= htmlspecialchars($priorityLabel, ENT_QUOTES) ?></strong></li>
      <li><span>Dernière candidature</span><strong><?= $latestApplication !== null ? htmlspecialchars((string) $latestApplication['statut'], ENT_QUOTES) : 'Aucune' ?></strong></li>
      <li><span>Accès direct</span><strong><a href="<?= htmlspecialchars(\Core\Url::route($priorityRoute), ENT_QUOTES) ?>"><?= htmlspecialchars($priorityAction, ENT_QUOTES) ?></a></strong></li>
    </ul>
    <p class="dashboard-card-note"><?= htmlspecialchars($priorityText, ENT_QUOTES) ?></p>
  </article>
</section>

<section class="section" aria-labelledby="section-plan-etudiant">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-plan-etudiant">Plan du moment</h2>
      <p class="section-subtitle">Deux repères pour savoir quoi faire ensuite sans perdre de temps.</p>
    </div>
  </div>

  <div class="action-grid action-grid--duo">
    <article class="action-card">
      <span class="pill-small">Priorité</span>
      <h3 class="action-card-title"><?= htmlspecialchars($priorityLabel, ENT_QUOTES) ?></h3>
      <p class="action-card-text"><?= htmlspecialchars($priorityText, ENT_QUOTES) ?></p>
      <a href="<?= htmlspecialchars(\Core\Url::route($priorityRoute), ENT_QUOTES) ?>" class="btn btn-primary"><?= htmlspecialchars($priorityAction, ENT_QUOTES) ?></a>
    </article>

    <article class="action-card">
      <span class="pill-small">Dossier</span>
      <h3 class="action-card-title">État de préparation</h3>
      <p class="action-card-text">
        <?= $hasCv && $hasLetter ? 'Votre dossier est complet pour candidater rapidement.' : 'Votre dossier est presque prêt. Il reste encore un peu de préparation.' ?>
      </p>
      <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>" class="btn btn-outline">Mettre à jour mes documents</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-actions-etudiant">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-actions-etudiant">Actions rapides</h2>
      <p class="section-subtitle">Les raccourcis essentiels pour poursuivre votre recherche sans d&eacute;tour.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">&#128188; Offres</span>
      <h3 class="action-card-title">Parcourir les offres</h3>
      <p class="action-card-text">Explorez de nouvelles opportunit&eacute;s et alimentez votre recherche avec des pistes concr&egrave;tes.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-outline">Voir les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">&#9829; Wish-list</span>
      <h3 class="action-card-title">Ouvrir ma wish-list</h3>
      <p class="action-card-text">Retrouvez les offres d&eacute;j&agrave; rep&eacute;r&eacute;es et comparez-les plus facilement.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('wishlist'), ENT_QUOTES) ?>" class="btn btn-outline">Voir ma wish-list</a>
    </article>

    <article class="action-card">
      <span class="pill-small">&#9733; Avis</span>
      <h3 class="action-card-title">Donner son avis sur la formation</h3>
      <p class="action-card-text">Partagez un retour utile sur l&rsquo;accompagnement et l&rsquo;organisation de la formation.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/avis'), ENT_QUOTES) ?>" class="btn btn-outline">Donner mon avis</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-candidatures">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-candidatures">Candidatures récentes</h2>
      <p class="section-subtitle">Retrouvez vos derniers envois et leur statut sans quitter le tableau de bord.</p>
    </div>
    <div class="section-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('candidatures'), ENT_QUOTES) ?>" class="link-soft">Voir toutes les candidatures &rarr;</a>
    </div>
  </div>

  <?php if ($applications !== []): ?>
    <div class="table-shell">
      <table class="data-table">
        <thead>
          <tr>
            <th>Offre</th>
            <th>Entreprise</th>
            <th>Statut</th>
            <th>CV</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $application): ?>
            <tr>
              <td><?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars((string) $application['entreprise_nom'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars((string) (($application['cv_path'] ?? '') !== '' ? $application['cv_path'] : 'Non renseigné'), ENT_QUOTES) ?></td>
              <td>
                <div class="table-actions">
                  <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $application['id_offre']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l’offre</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <h3 class="empty-state-title">Aucune candidature envoyée</h3>
      <p class="empty-state-text">Votre espace est prêt. Enregistrez quelques offres, puis envoyez vos premières candidatures avec un dossier complet.</p>
      <div class="empty-state-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-outline">Voir les offres</a>
        <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>" class="btn btn-primary">Préparer mes documents</a>
      </div>
    </div>
  <?php endif; ?>
</section>

<section class="section" aria-labelledby="section-companies">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-companies">Entreprises à explorer</h2>
      <p class="section-subtitle">Une sélection d’entreprises actives pour repérer rapidement des pistes pertinentes.</p>
    </div>
  </div>

  <?php if ($featuredCompanies !== []): ?>
    <div class="dashboard-grid dashboard-grid--three">
      <?php foreach ($featuredCompanies as $company): ?>
        <article class="dash-card">
          <header class="dash-card-header">
            <span class="dash-card-title"><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
            <span class="pill-small"><?= (int) ($company['offers_count'] ?? 0) ?> offre<?= (int) ($company['offers_count'] ?? 0) > 1 ? 's' : '' ?></span>
          </header>
          <ul class="list-compact">
            <li><span>Ville</span><strong><?= htmlspecialchars((string) (($company['ville'] ?? '') !== '' ? $company['ville'] : 'Non précisée'), ENT_QUOTES) ?></strong></li>
            <li><span>Secteur</span><strong><?= htmlspecialchars((string) (($company['secteur'] ?? '') !== '' ? $company['secteur'] : 'Non renseigné'), ENT_QUOTES) ?></strong></li>
            <li><span>Note</span><strong><?= ($company['average_rating'] ?? null) !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . '/5' : 'Aucune note' ?></strong></li>
          </ul>
          <p class="dashboard-card-note">
            <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="link-soft">Voir la fiche entreprise &rarr;</a>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="dash-card">
      <p class="action-card-text">Aucune entreprise mise en avant pour le moment.</p>
    </div>
  <?php endif; ?>
</section>

