<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord étudiant</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($studentName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Étudiant</span>
    <p class="dashboard-subtitle">
      Suivez vos offres, vos candidatures, vos documents et vos retours directement depuis votre espace.
    </p>
  </div>
</header>

<section class="dashboard-grid" aria-label="Résumé de votre activité">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue d ensemble</span>
      <span class="pill-small">Recherche en cours</span>
    </header>
    <ul class="list-compact">
      <li><span>Offres en favoris</span><strong><?= (int) $stats['wishlist'] ?></strong></li>
      <li><span>Candidatures envoyées</span><strong><?= (int) $stats['applications'] ?></strong></li>
      <li><span>Entretiens prévus</span><strong><?= (int) $stats['interviews'] ?></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Statuts</span>
      <span class="pill-small">Suivi</span>
    </header>
    <ul class="list-compact">
      <li><span>En attente</span><strong><?= (int) $stats['pending'] ?></strong></li>
      <li><span>Acceptées</span><strong><?= (int) $stats['accepted'] ?></strong></li>
      <li><span>Refusées</span><strong><?= (int) $stats['rejected'] ?></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Mon dossier</span>
      <span class="pill-small">Documents</span>
    </header>
    <ul class="list-compact">
      <li><span>CV enregistré</span><strong><?= !empty($documents['cv_path']) ? 'Oui' : 'À ajouter' ?></strong></li>
      <li><span>Lettre type</span><strong><?= !empty($documents['lettre_type']) ? 'Disponible' : 'À compléter' ?></strong></li>
      <li><span>Accès rapide</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>">Mettre à jour</a></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Mon espace</span>
      <span class="pill-small">Actions</span>
    </header>
    <ul class="list-compact">
      <li><span>Voir la wish-list</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('wishlist'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
      <li><span>Voir mes candidatures</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('candidatures'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
      <li><span>Parcourir les offres</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
  </article>
</section>

<section class="section" aria-labelledby="section-actions-etudiant">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-actions-etudiant">Actions rapides</h2>
      <p class="section-subtitle">Les raccourcis utiles pour continuer votre recherche sans perdre de temps.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">Retour</span>
      <h3 class="action-card-title">Donner son avis sur la formation</h3>
      <p class="action-card-text">Ajoutez un avis sur l accompagnement et consultez les retours déjà publiés.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/avis'), ENT_QUOTES) ?>" class="btn btn-outline">Donner mon avis</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Évaluation</span>
      <h3 class="action-card-title">Évaluer une entreprise</h3>
      <p class="action-card-text">Partagez votre ressenti après un échange ou une expérience de stage.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/entreprises/evaluer'), ENT_QUOTES) ?>" class="btn btn-outline">Ajouter un avis</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Documents</span>
      <h3 class="action-card-title">Mettre à jour CV et lettre</h3>
      <p class="action-card-text">Préparez vos prochains envois avec un dossier candidat toujours prêt.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>" class="btn btn-outline">Mettre à jour</a>
    </article>
  </div>
</section>

<section class="section" aria-labelledby="section-wishlist">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-wishlist">Offres en favoris</h2>
      <p class="section-subtitle">Offres enregistrées pour suivi et candidature.</p>
    </div>
    <div class="section-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('wishlist'), ENT_QUOTES) ?>" class="link-soft">Voir toute la wish-list -></a>
    </div>
  </div>

  <?php if (!empty($wishlistOffers)): ?>
    <div class="offers-grid">
      <?php foreach ($wishlistOffers as $offer): ?>
        <article class="offer-card">
          <header class="offer-card-header">
            <div class="offer-badge"><?= htmlspecialchars((string) $offer['badge'], ENT_QUOTES) ?></div>
            <div>
              <h3 class="offer-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h3>
              <p class="offer-company"><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></p>
            </div>
          </header>
          <div class="offer-meta">
            <span><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
            <span><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></span>
          </div>
          <div class="offer-skills">
            <?php foreach (($offer['skills'] ?? []) as $skill): ?>
              <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
          </div>
          <footer class="offer-footer">
            <div class="offer-tagline"><?= htmlspecialchars((string) $offer['tagline'], ENT_QUOTES) ?></div>
            <div class="offer-actions">
              <form method="post" action="<?= htmlspecialchars(\Core\Url::route('wishlist/toggle'), ENT_QUOTES) ?>">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
                <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
                <input type="hidden" name="redirect_to" value="dashboard-etudiant" />
                <button type="submit" class="btn-icon btn-icon--wish active" aria-label="Retirer des favoris">♥</button>
              </form>
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">Détails</a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="dash-card">
      <p class="action-card-text">Aucune offre n est encore enregistrée dans votre wish-list.</p>
    </div>
  <?php endif; ?>
</section>

<section class="section" aria-labelledby="section-candidatures">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-candidatures">Candidatures récentes</h2>
      <p class="section-subtitle">Suivez les dernières démarches envoyées depuis votre espace.</p>
    </div>
    <div class="section-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('candidatures'), ENT_QUOTES) ?>" class="link-soft">Voir toutes les candidatures -></a>
    </div>
  </div>

  <?php if (!empty($applications)): ?>
    <div class="dashboard-grid">
      <?php foreach ($applications as $application): ?>
        <article class="dash-card">
          <header class="dash-card-header">
            <span class="dash-card-title"><?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></span>
            <span class="pill-small"><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></span>
          </header>
          <ul class="list-compact">
            <li><span>Entreprise</span><strong><?= htmlspecialchars((string) $application['entreprise_nom'], ENT_QUOTES) ?></strong></li>
            <li><span>CV</span><strong><?= htmlspecialchars((string) ($application['cv_path'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong></li>
          </ul>
          <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $application['id_offre']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l offre</a>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="dash-card">
      <p class="action-card-text">Aucune candidature n a encore été envoyée.</p>
    </div>
  <?php endif; ?>
</section>
