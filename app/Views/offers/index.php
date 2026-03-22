<?php
  $filters = is_array($filters ?? null) ? $filters : [];
  $wishlistIds = is_array($wishlistIds ?? null) ? $wishlistIds : [];
  $returnToValue = 'offres' . (!empty($_SERVER['QUERY_STRING']) ? '?' . (string) $_SERVER['QUERY_STRING'] : '');
  $buildPageUrl = static function (int $page) use ($filters): string {
    $query = array_filter([
      'page' => $page,
      'keyword' => (string) ($filters['keyword'] ?? ''),
      'city' => (string) ($filters['city'] ?? ''),
      'skill' => (string) ($filters['skill'] ?? ''),
      'duration' => (string) ($filters['duration'] ?? ''),
    ], static fn ($value): bool => $value !== '');

    return htmlspecialchars(\Core\Url::route('offres') . ($query !== [] ? '?' . http_build_query($query) : ''), ENT_QUOTES);
  };
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Catalogue des offres</span>
    <h1 class="page-heading-title">Toutes les offres de stage</h1>
    <p class="page-heading-subtitle">
      Filtrez les offres par compétence, ville et durée, puis enregistrez les plus pertinentes.
    </p>
  </div>
</header>

<section class="search-section" aria-label="Filtres d'offres">
  <form method="get" action="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="search-grid">
    <div>
      <label class="field-label" for="mot-cle-liste">Mot-clé</label>
      <input
        type="text"
        id="mot-cle-liste"
        name="keyword"
        class="field-input"
        value="<?= htmlspecialchars((string) ($filters['keyword'] ?? ''), ENT_QUOTES) ?>"
        placeholder="Ex : développeur, UX, marketing..."
      />
    </div>
    <div>
      <label class="field-label" for="ville-liste">Ville</label>
      <input
        type="text"
        id="ville-liste"
        name="city"
        class="field-input"
        value="<?= htmlspecialchars((string) ($filters['city'] ?? ''), ENT_QUOTES) ?>"
        placeholder="Ex : Bordeaux, Toulouse..."
      />
    </div>
    <div>
      <label class="field-label" for="competence-liste">Compétence</label>
      <select id="competence-liste" name="skill" class="field-select">
        <option value="">Toutes</option>
        <?php foreach (($skillOptions ?? []) as $skillOption): ?>
          <option value="<?= htmlspecialchars((string) $skillOption, ENT_QUOTES) ?>" <?= (string) ($filters['skill'] ?? '') === (string) $skillOption ? 'selected' : '' ?>>
            <?= htmlspecialchars((string) $skillOption, ENT_QUOTES) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="field-label" for="duree">Durée</label>
      <select id="duree" name="duration" class="field-select">
        <option value="">Toutes durées</option>
        <option value="2-3" <?= (string) ($filters['duration'] ?? '') === '2-3' ? 'selected' : '' ?>>2-3 mois</option>
        <option value="4-6" <?= (string) ($filters['duration'] ?? '') === '4-6' ? 'selected' : '' ?>>4-6 mois</option>
        <option value="6-plus" <?= (string) ($filters['duration'] ?? '') === '6-plus' ? 'selected' : '' ?>>6 mois et +</option>
      </select>
    </div>
    <div class="search-actions">
      <button type="submit" class="btn btn-primary btn-full">Filtrer</button>
    </div>
  </form>
</section>

<section class="section" aria-label="Liste d'offres">
  <div class="section-header">
    <div>
      <h2 class="section-title"><?= (int) ($totalOffers ?? 0) ?> offre(s) trouvée(s)</h2>
      <p class="section-subtitle">Résultats mis à jour selon les filtres actifs.</p>
    </div>
    <?php if (\Core\Auth::checkRole(\Core\Auth::ROLE_ETUDIANT)): ?>
      <div class="section-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('wishlist'), ENT_QUOTES) ?>" class="link-soft">Voir ma wish-list -></a>
      </div>
    <?php endif; ?>
  </div>

  <?php if (!empty($offers)): ?>
    <div class="offers-grid">
      <?php foreach ($offers as $offer): ?>
        <?php $image = \Core\Url::asset('assets/img/offers/' . ((string) ($offer['image'] ?? 'default.svg'))); ?>
        <article class="offer-card offer-card--with-thumb">
          <div class="offer-cover offer-cover--thumb">
            <img
              src="<?= htmlspecialchars($image, ENT_QUOTES) ?>"
              alt="Illustration de l'offre <?= htmlspecialchars((string) ($offer['title'] ?? ''), ENT_QUOTES) ?>"
            />
          </div>
          <header class="offer-card-header offer-card-header--thumb">
            <div class="offer-badge"><?= htmlspecialchars((string) $offer['badge'], ENT_QUOTES) ?></div>
            <div>
              <h2 class="offer-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h2>
              <p class="offer-company">
                <?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?>
                <?php if (!empty($offer['city'])): ?>
                  · <?= htmlspecialchars((string) $offer['city'], ENT_QUOTES) ?>
                <?php endif; ?>
              </p>
            </div>
          </header>
          <div class="offer-meta">
            <span><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
            <span><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></span>
            <span>Publié le <?= htmlspecialchars((string) $offer['published'], ENT_QUOTES) ?></span>
          </div>
          <div class="offer-skills">
            <?php foreach (($offer['skills'] ?? []) as $skill): ?>
              <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
          </div>
          <footer class="offer-footer">
            <div class="offer-tagline"><?= htmlspecialchars((string) $offer['tagline'], ENT_QUOTES) ?></div>
            <div class="offer-actions">
              <?php if (\Core\Auth::checkRole(\Core\Auth::ROLE_ETUDIANT)): ?>
                <form method="post" action="<?= htmlspecialchars(\Core\Url::route('wishlist/toggle'), ENT_QUOTES) ?>">
                  <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
                  <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
                  <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($returnToValue, ENT_QUOTES) ?>" />
                  <button type="submit" class="btn-icon btn-icon--wish<?= in_array((int) $offer['id'], $wishlistIds, true) ? ' active' : '' ?>" aria-label="Modifier la wish-list">
                    ♥
                  </button>
                </form>
              <?php endif; ?>
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">
                Détails
              </a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <span class="pill-small">Aucun résultat</span>
      <h2 class="empty-state-title">Aucune offre ne correspond à vos filtres</h2>
      <p class="empty-state-text">Modifiez un critère ou revenez au catalogue complet.</p>
      <div class="empty-state-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">Réinitialiser</a>
      </div>
    </div>
  <?php endif; ?>

  <?php if (($totalPages ?? 1) > 1): ?>
    <nav class="pagination" aria-label="Pagination des offres">
      <?php if (($currentPage ?? 1) > 1): ?>
        <a class="page-btn" href="<?= $buildPageUrl(((int) $currentPage) - 1) ?>">«</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">«</span>
      <?php endif; ?>

      <?php for ($page = 1; $page <= (int) ($totalPages ?? 1); $page++): ?>
        <a class="page-btn<?= $page === (int) ($currentPage ?? 1) ? ' page-btn--active' : '' ?>" href="<?= $buildPageUrl($page) ?>">
          <?= $page ?>
        </a>
      <?php endfor; ?>

      <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
        <a class="page-btn" href="<?= $buildPageUrl(((int) $currentPage) + 1) ?>">»</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">»</span>
      <?php endif; ?>
    </nav>
  <?php endif; ?>
</section>
