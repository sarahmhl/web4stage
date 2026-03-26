<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Candidatures</span>
    <h1 class="page-heading-title">Mes candidatures</h1>
    <p class="page-heading-subtitle">
      Suivez vos envois dans une liste plus simple, avec les infos utiles et les actions principales.
    </p>
  </div>
</header>

<?php if (!empty($applications)): ?>
  <section class="application-simple-list" aria-label="Liste des candidatures">
    <?php foreach ($applications as $application): ?>
      <article class="dash-card application-simple-card">
        <header class="application-simple-head">
          <div class="application-simple-main">
            <span class="application-simple-kicker">Candidature envoyée</span>
            <h2 class="application-simple-title"><?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></h2>
            <p class="application-simple-company">
              <?= htmlspecialchars((string) $application['entreprise_nom'], ENT_QUOTES) ?>
              <span>·</span>
              <?= htmlspecialchars(date('d/m/Y', strtotime((string) $application['created_at'])), ENT_QUOTES) ?>
            </p>
          </div>
          <span class="pill-small"><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></span>
        </header>

        <div class="application-simple-meta">
          <span class="application-simple-chip"><?= !empty($application['cv_path']) ? 'CV joint' : 'CV non renseigné' ?></span>
          <?php if (!empty($application['commentaire'])): ?>
            <span class="application-simple-chip">Commentaire présent</span>
          <?php endif; ?>
        </div>

        <div class="application-simple-grid">
          <section class="application-simple-block">
            <h3>Lettre de motivation</h3>
            <p><?= nl2br(htmlspecialchars((string) ($application['lettre_motivation'] ?: 'Aucune lettre enregistrée.'), ENT_QUOTES)) ?></p>
          </section>

          <?php if (!empty($application['commentaire'])): ?>
            <section class="application-simple-block">
              <h3>Commentaire</h3>
              <p><?= nl2br(htmlspecialchars((string) $application['commentaire'], ENT_QUOTES)) ?></p>
            </section>
          <?php endif; ?>
        </div>

        <div class="table-actions application-simple-actions">
          <?php if (!empty($application['cv_path'])): ?>
            <a href="<?= htmlspecialchars(\Core\Url::asset((string) $application['cv_path']), ENT_QUOTES) ?>" target="_blank" rel="noreferrer" class="btn btn-outline">Consulter le CV</a>
          <?php endif; ?>
          <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $application['id_offre']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l’offre</a>
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $application['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Voir l’entreprise</a>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
<?php else: ?>
  <section class="empty-state">
    <span class="pill-small">Aucune candidature</span>
    <h1 class="empty-state-title">Vous n’avez pas encore postulé</h1>
    <p class="empty-state-text">Parcourez les offres puis candidatez directement depuis leur fiche détaillée.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les offres</a>
    </div>
  </section>
<?php endif; ?>
