<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Qualité</span>
    <h1 class="page-heading-title">Indicateurs de qualité globale</h1>
    <p class="page-heading-subtitle">
      Gardez une vue synthétique sur les offres actives et le portefeuille d’entreprises.
    </p>
  </div>
</header>

<section class="dashboard-grid">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Offres en base</span>
      <span class="pill-small"><?= (int) ($offersTotalItems ?? count($offers ?? [])) ?> offres</span>
    </header>
    <ul class="list-compact">
      <?php foreach (($offers ?? []) as $offer): ?>
        <li>
          <span><?= htmlspecialchars((string) $offer['titre'], ENT_QUOTES) ?></span>
          <strong><?= htmlspecialchars((string) $offer['entreprise_nom'], ENT_QUOTES) ?></strong>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
    $paginationCurrentPage = (int) ($offersCurrentPage ?? 1);
    $paginationTotalPages = (int) ($offersTotalPages ?? 1);
    $paginationPageParam = 'offers_page';
    $paginationLabel = 'Pagination des offres de qualité';
    require __DIR__ . '/../partials/pagination.php';
    ?>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Entreprises référencées</span>
      <span class="pill-small"><?= (int) ($companiesTotalItems ?? count($companies ?? [])) ?> entreprises</span>
    </header>
    <ul class="list-compact">
      <?php foreach (($companies ?? []) as $company): ?>
        <li>
          <span><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
          <strong><?= (int) ($company['offers_count'] ?? 0) ?> offre(s)</strong>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
    $paginationCurrentPage = (int) ($companiesCurrentPage ?? 1);
    $paginationTotalPages = (int) ($companiesTotalPages ?? 1);
    $paginationPageParam = 'companies_page';
    $paginationLabel = 'Pagination des entreprises de qualité';
    require __DIR__ . '/../partials/pagination.php';
    ?>
  </article>
</section>
