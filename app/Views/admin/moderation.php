<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Modération</span>
    <h1 class="page-heading-title">Avis et retours utilisateurs</h1>
    <p class="page-heading-subtitle">
      Relisez les avis étudiants et les évaluations entreprises publiés sur la plateforme.
    </p>
  </div>
</header>

<section class="dashboard-grid">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Avis étudiants</span>
      <span class="pill-small"><?= count($feedbacks ?? []) ?> avis</span>
    </header>
    <ul class="list-compact">
      <?php foreach (array_slice($feedbacks ?? [], 0, 8) as $feedback): ?>
        <li>
          <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?> · <?= htmlspecialchars((string) $feedback['commentaire'], ENT_QUOTES) ?></span>
          <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
        </li>
      <?php endforeach; ?>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Évaluations entreprises</span>
      <span class="pill-small"><?= count($companyReviews ?? []) ?> évaluations</span>
    </header>
    <ul class="list-compact">
      <?php foreach (array_slice($companyReviews ?? [], 0, 8) as $review): ?>
        <li>
          <span><?= htmlspecialchars((string) $review['entreprise_nom'], ENT_QUOTES) ?> · <?= htmlspecialchars((string) $review['commentaire'], ENT_QUOTES) ?></span>
          <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
        </li>
      <?php endforeach; ?>
    </ul>
  </article>
</section>
