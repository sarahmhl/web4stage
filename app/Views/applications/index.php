<?php // Vue de la liste des candidatures deja envoyees par l etudiant connecte. ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Candidatures</span>
    <h1 class="page-heading-title">Mes candidatures</h1>
    <p class="page-heading-subtitle">
      Suivez l etat de vos envois, les CV utilises et les lettres de motivation associees.
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
            <strong>
              <?php if (!empty($application['cv_path'])): ?>
                <a href="<?= htmlspecialchars(\Core\Url::asset((string) $application['cv_path']), ENT_QUOTES) ?>" target="_blank" rel="noreferrer">
                  Consulter le CV
                </a>
              <?php else: ?>
                Non renseigne
              <?php endif; ?>
            </strong>
          </li>
          <li>
            <span>Envoyee le</span>
            <strong><?= htmlspecialchars(date('d/m/Y', strtotime((string) $application['created_at'])), ENT_QUOTES) ?></strong>
          </li>
        </ul>
        <section class="detail-section detail-section--compact">
          <h2>Lettre de motivation</h2>
          <p><?= nl2br(htmlspecialchars((string) ($application['lettre_motivation'] ?: 'Aucune lettre enregistree.'), ENT_QUOTES)) ?></p>
        </section>
        <?php if (!empty($application['commentaire'])): ?>
          <section class="detail-section detail-section--compact">
            <h2>Commentaire</h2>
            <p><?= nl2br(htmlspecialchars((string) $application['commentaire'], ENT_QUOTES)) ?></p>
          </section>
        <?php endif; ?>
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
    <h1 class="empty-state-title">Vous n avez pas encore postule</h1>
    <p class="empty-state-text">Parcourez les offres puis candidatez directement depuis leur fiche detaillee.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les offres</a>
    </div>
  </section>
<?php endif; ?>
