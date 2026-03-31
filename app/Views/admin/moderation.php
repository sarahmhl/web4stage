<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Modération</span>
    <h1 class="page-heading-title">Avis et retours utilisateurs</h1>
    <p class="page-heading-subtitle">
      Relisez les avis étudiants et les évaluations d'entreprises publiés sur la plateforme.
    </p>
  </div>
</header>

<section class="dashboard-grid page-fill-grid">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Avis étudiants</span>
      <span class="pill-small"><?= (int) ($feedbackTotalItems ?? count($feedbacks ?? [])) ?> avis</span>
    </header>
    <ul class="list-compact">
      <?php foreach (($feedbacks ?? []) as $feedback): ?>
        <li>
          <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?> · <?= htmlspecialchars((string) $feedback['commentaire'], ENT_QUOTES) ?></span>
          <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
    $paginationCurrentPage = (int) ($feedbackCurrentPage ?? 1);
    $paginationTotalPages = (int) ($feedbackTotalPages ?? 1);
    $paginationPageParam = 'feedback_page';
    $paginationLabel = 'Pagination des avis étudiants';
    require __DIR__ . '/../partials/pagination.php';
    ?>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Évaluations entreprises</span>
      <span class="pill-small"><?= (int) ($companyReviewTotalItems ?? count($companyReviews ?? [])) ?> évaluations</span>
    </header>
    <ul class="list-compact">
      <?php foreach (($companyReviews ?? []) as $review): ?>
        <li>
          <span><?= htmlspecialchars((string) $review['entreprise_nom'], ENT_QUOTES) ?> · <?= htmlspecialchars((string) $review['commentaire'], ENT_QUOTES) ?></span>
          <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
    $paginationCurrentPage = (int) ($companyReviewCurrentPage ?? 1);
    $paginationTotalPages = (int) ($companyReviewTotalPages ?? 1);
    $paginationPageParam = 'company_review_page';
    $paginationLabel = 'Pagination des évaluations d\'entreprises';
    require __DIR__ . '/../partials/pagination.php';
    ?>
  </article>
</section>
