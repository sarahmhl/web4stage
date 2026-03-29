<?php
$filters = is_array($filters ?? null) ? $filters : [];
$buildPageUrl = static function (int $page) use ($filters): string {
  $query = array_filter([
    'page' => $page,
    'keyword' => (string) ($filters['keyword'] ?? ''),
    'city' => (string) ($filters['city'] ?? ''),
    'sector' => (string) ($filters['sector'] ?? ''),
  ], static fn ($value): bool => $value !== '');

  return htmlspecialchars(\Core\Url::route('entreprises') . ($query !== [] ? '?' . http_build_query($query) : ''), ENT_QUOTES);
};
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Entreprises</span>
    <h1 class="page-heading-title">Entreprises référencées</h1>
    <p class="page-heading-subtitle">
      Consultez les structures partenaires, leurs coordonnées et les offres de stage liées.
    </p>
  </div>
</header>

<section class="search-section search-section--compact" aria-label="Recherche d’entreprises">
  <form method="get" action="<?= htmlspecialchars(\Core\Url::route('entreprises'), ENT_QUOTES) ?>" class="search-grid" data-js-validate>
    <div>
      <label class="field-label" for="company-keyword">Mot-clé</label>
      <input
        type="text"
        id="company-keyword"
        name="keyword"
        class="field-input"
        value="<?= htmlspecialchars((string) ($filters['keyword'] ?? ''), ENT_QUOTES) ?>"
        placeholder="Ex : agence web, data, design..."
      />
    </div>
    <div>
      <label class="field-label" for="company-city">Ville</label>
      <input
        type="text"
        id="company-city"
        name="city"
        class="field-input"
        value="<?= htmlspecialchars((string) ($filters['city'] ?? ''), ENT_QUOTES) ?>"
        placeholder="Ex : Paris, Bordeaux..."
      />
    </div>
    <div>
      <label class="field-label" for="company-sector">Secteur</label>
      <input
        type="text"
        id="company-sector"
        name="sector"
        class="field-input"
        value="<?= htmlspecialchars((string) ($filters['sector'] ?? ''), ENT_QUOTES) ?>"
        placeholder="Ex : Développement web, UX/UI..."
      />
    </div>
    <div class="search-actions">
      <button type="submit" class="btn btn-primary btn-full">Filtrer</button>
    </div>
  </form>
</section>

<?php if (!empty($companies)): ?>
  <section class="dashboard-grid">
    <?php foreach (($companies ?? []) as $company): ?>
      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title"><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
          <span class="pill-small"><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></span>
        </header>
        <p class="action-card-text"><?= htmlspecialchars((string) ($company['description'] ?: 'Aucune description disponible pour le moment.'), ENT_QUOTES) ?></p>
        <ul class="list-compact">
          <li>
            <span>Secteur</span>
            <strong><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Offres publiées</span>
            <strong><?= (int) ($company['offers_count'] ?? 0) ?></strong>
          </li>
          <li>
            <span>Note moyenne</span>
            <strong><?= $company['average_rating'] !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . ' / 5' : 'Aucune note' ?></strong>
          </li>
        </ul>
        <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Voir la fiche</a>
      </article>
    <?php endforeach; ?>
  </section>

  <?php if (($totalPages ?? 1) > 1): ?>
    <nav class="pagination" aria-label="Pagination des entreprises">
      <?php if (($currentPage ?? 1) > 1): ?>
        <a class="page-btn" href="<?= $buildPageUrl(((int) $currentPage) - 1) ?>">&laquo;</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">&laquo;</span>
      <?php endif; ?>

      <?php for ($page = 1; $page <= (int) ($totalPages ?? 1); $page++): ?>
        <a class="page-btn<?= $page === (int) ($currentPage ?? 1) ? ' page-btn--active' : '' ?>" href="<?= $buildPageUrl($page) ?>">
          <?= $page ?>
        </a>
      <?php endfor; ?>

      <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
        <a class="page-btn" href="<?= $buildPageUrl(((int) $currentPage) + 1) ?>">&raquo;</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">&raquo;</span>
      <?php endif; ?>
    </nav>
  <?php endif; ?>
<?php else: ?>
  <section class="empty-state">
    <span class="pill-small">Aucun résultat</span>
    <h1 class="empty-state-title">Aucune entreprise ne correspond à votre recherche</h1>
    <p class="empty-state-text">Modifiez les filtres pour afficher de nouvelles structures partenaires.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Réinitialiser les filtres</a>
    </div>
  </section>
<?php endif; ?>
