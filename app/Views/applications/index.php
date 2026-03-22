<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Candidatures</span>
    <h1 class="page-heading-title">Mes candidatures</h1>
    <p class="page-heading-subtitle">
      Suivez l état de vos envois, les CV utilisés et les lettres de motivation associées.
    </p>
  </div>
</header>

<?php if (!empty($applications)): ?>
  <section class="dashboard-grid">
    <?php foreach ($applications as $application): ?>
      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title"><?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></span>
          <span class="pill-small"><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></span>
        </header>
        <ul class="list-compact">
          <li>
            <span>Entreprise</span>
            <strong><?= htmlspecialchars((string) $application['entreprise_nom'], ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>CV</span>
            <strong><?= htmlspecialchars((string) ($application['cv_path'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Envoyée le</span>
            <strong><?= htmlspecialchars(date('d/m/Y', strtotime((string) $application['created_at'])), ENT_QUOTES) ?></strong>
          </li>
        </ul>
        <section class="detail-section detail-section--compact">
          <h2>Lettre de motivation</h2>
          <p><?= nl2br(htmlspecialchars((string) ($application['lettre_motivation'] ?: 'Aucune lettre enregistrée.'), ENT_QUOTES)) ?></p>
        </section>
        <div class="detail-actions">
          <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $application['id_offre']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l offre</a>
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $application['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l entreprise</a>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
<?php else: ?>
  <section class="empty-state">
    <span class="pill-small">Aucune candidature</span>
    <h1 class="empty-state-title">Vous n avez pas encore postulé</h1>
    <p class="empty-state-text">Parcourez les offres puis candidatez directement depuis leur fiche détaillée.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les offres</a>
    </div>
  </section>
<?php endif; ?>
