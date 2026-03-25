<?php
$filters = is_array($filters ?? null) ? $filters : [];
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Entreprises</span>
    <h1 class="page-heading-title">Entreprises referencees</h1>
    <p class="page-heading-subtitle">
      Consultez les structures partenaires, leurs coordonnees et les offres de stage liees.
    </p>
  </div>
</header>

<section class="search-section search-section--compact" aria-label="Recherche d entreprises">
  <form method="get" action="<?= htmlspecialchars(\Core\Url::route('entreprises'), ENT_QUOTES) ?>" class="search-grid" data-js-validate>
    <div>
      <label class="field-label" for="company-keyword">Mot-cle</label>
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
        placeholder="Ex : Developpement web, UX/UI..."
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
          <span class="pill-small"><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non precisee'), ENT_QUOTES) ?></span>
        </header>
        <p class="action-card-text"><?= htmlspecialchars((string) ($company['description'] ?: 'Aucune description disponible pour le moment.'), ENT_QUOTES) ?></p>
        <ul class="list-compact">
          <li>
            <span>Secteur</span>
            <strong><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigne'), ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Offres publiees</span>
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
<?php else: ?>
  <section class="empty-state">
    <span class="pill-small">Aucun resultat</span>
    <h1 class="empty-state-title">Aucune entreprise ne correspond a votre recherche</h1>
    <p class="empty-state-text">Modifiez les filtres pour afficher de nouvelles structures partenaires.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Reinitialiser les filtres</a>
    </div>
  </section>
<?php endif; ?>
