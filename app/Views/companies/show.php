<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Fiche entreprise</span>
    <h1 class="page-heading-title"><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      Consultez les coordonnées, les avis étudiants et les offres de stage associées.
    </p>
  </div>
</header>

<section class="page-layout detail-layout">
  <article class="detail-card">
    <div class="detail-content detail-content--full">
      <div class="detail-stats">
        <div class="stat-pill">
          <span>Ville</span>
          <strong><?= htmlspecialchars((string) ($company['ville'] ?: 'Non précisée'), ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Secteur</span>
          <strong><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Offres liées</span>
          <strong><?= (int) ($company['offers_count'] ?? 0) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Note moyenne</span>
          <strong><?= $company['average_rating'] !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . ' / 5' : 'Aucune note' ?></strong>
        </div>
      </div>

      <section class="detail-section">
        <h2>Présentation</h2>
        <p><?= nl2br(htmlspecialchars((string) ($company['description'] ?: 'Aucune description disponible pour le moment.'), ENT_QUOTES)) ?></p>
      </section>

      <section class="detail-section">
        <h2>Coordonnées</h2>
        <div class="detail-meta-grid">
          <div>
            <span class="detail-label">Email</span>
            <?php if (!empty($company['email_contact'])): ?>
              <a class="detail-link" href="mailto:<?= htmlspecialchars((string) $company['email_contact'], ENT_QUOTES) ?>">
                <strong class="detail-value"><?= htmlspecialchars((string) $company['email_contact'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseigné</strong>
            <?php endif; ?>
          </div>
          <div>
            <span class="detail-label">Téléphone</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($company['telephone_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </div>
          <div>
            <span class="detail-label">Site</span>
            <?php if (!empty($company['site_web'])): ?>
              <a
                class="detail-link"
                href="<?= htmlspecialchars((string) $company['site_web'], ENT_QUOTES) ?>"
                target="_blank"
                rel="noreferrer"
              >
                <strong class="detail-value"><?= htmlspecialchars((string) $company['site_web'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseigné</strong>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <?php if (!empty($canReview)): ?>
        <div class="detail-actions">
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-primary">
            Évaluer cette entreprise
          </a>
        </div>
      <?php endif; ?>
    </div>
  </article>

  <aside class="side-card">
    <h2 class="side-card-title">Actions liées</h2>
    <p class="side-card-text">
      Explorez les offres de cette entreprise ou ajoutez un retour étudiant.
    </p>
    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Retour aux offres</strong>
        <span>Continuer la recherche</span>
      </a>
      <?php if (!empty($canReview)): ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="management-offer-link">
          <strong>Laisser une évaluation</strong>
          <span>Partager votre retour sur l’entreprise</span>
        </a>
      <?php endif; ?>
    </div>
  </aside>
</section>

<?php if (!empty($company['offers'])): ?>
  <section class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Offres liées</h2>
        <p class="section-subtitle">Stages actuellement proposés par cette entreprise.</p>
      </div>
    </div>

    <div class="offers-grid">
      <?php foreach ($company['offers'] as $offer): ?>
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
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">Détails</a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Avis étudiants</h2>
      <p class="section-subtitle">Retours déjà publiés sur cette entreprise.</p>
    </div>
  </div>

  <?php if (!empty($company['reviews'])): ?>
    <div class="dashboard-grid">
      <?php foreach ($company['reviews'] as $review): ?>
        <article class="dash-card">
          <header class="dash-card-header">
            <span class="dash-card-title"><?= htmlspecialchars(trim((string) $review['prenom'] . ' ' . (string) $review['nom']), ENT_QUOTES) ?></span>
            <span class="pill-small"><?= (int) ($review['note'] ?? 0) ?>/5</span>
          </header>
          <p class="action-card-text"><?= nl2br(htmlspecialchars((string) $review['commentaire'], ENT_QUOTES)) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="dash-card">
      <p class="action-card-text">Aucun avis n’a encore été publié pour cette entreprise.</p>
    </div>
  <?php endif; ?>
</section>
