<?php
  $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
  $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
  $projectBase = $baseDir === '' ? '' : rtrim(str_replace('\\', '/', dirname($baseDir)), '/');
  $assetBase = str_replace(' ', '%20', $projectBase);
  $basePath = '/assets/img/offers/';
  $offersDir = dirname(__DIR__, 3) . '/assets/img/offers';
  if (!is_dir($offersDir)) {
    $offersDir = dirname(__DIR__, 2) . '/assets/img/offers';
  }

  $availableOfferImages = [];
  if (is_dir($offersDir)) {
    foreach (scandir($offersDir) ?: [] as $entry) {
      if ($entry === '.' || $entry === '..') {
        continue;
      }
      $availableOfferImages[mb_strtolower($entry)] = $entry;
    }
  }

  $findOfferImage = static function (string $requested) use ($availableOfferImages): ?string {
    $requested = ltrim(trim($requested), '/');
    if ($requested === '') {
      return null;
    }

    $lowerRequested = mb_strtolower($requested);
    if (isset($availableOfferImages[$lowerRequested])) {
      return $availableOfferImages[$lowerRequested];
    }

    $stem = mb_strtolower(pathinfo($requested, PATHINFO_FILENAME));
    if ($stem === '') {
      return null;
    }

    foreach (['jpg', 'jpeg', 'png', 'webp', 'svg'] as $ext) {
      $candidate = $stem . '.' . $ext;
      if (isset($availableOfferImages[$candidate])) {
        return $availableOfferImages[$candidate];
      }
    }

    return null;
  };

  $toOfferImageUrl = static function (string $fileName) use ($assetBase, $basePath, $offersDir): string {
    $url = ($assetBase === '' ? '' : $assetBase) . $basePath . $fileName;
    $mtime = @filemtime($offersDir . DIRECTORY_SEPARATOR . $fileName);
    if ($mtime !== false) {
      $url .= '?v=' . $mtime;
    }

    return $url;
  };

  $defaultOfferImage = $findOfferImage('default.svg')
    ?? $findOfferImage('design')
    ?? $findOfferImage('marketing')
    ?? null;
  $defaultOfferImageUrl = $defaultOfferImage !== null
    ? $toOfferImageUrl($defaultOfferImage)
    : (($assetBase === '' ? '' : $assetBase) . $basePath . 'default.svg');
  $routePrefix = rtrim($scriptName, '/');
  $offersPageUrl = static function (int $page) use ($routePrefix): string {
    return htmlspecialchars(str_replace(' ', '%20', $routePrefix . '/offres?page=' . $page), ENT_QUOTES);
  };

  $resolveOfferImage = static function (array $offer) use ($findOfferImage, $toOfferImageUrl, $defaultOfferImageUrl): string {
    $title = mb_strtolower((string) ($offer['title'] ?? ''));

    // Priorite 1: image explicite depuis les donnees.
    if (!empty($offer['image']) && is_string($offer['image'])) {
      $explicitImage = $findOfferImage((string) $offer['image']);
      if ($explicitImage !== null) {
        return $toOfferImageUrl($explicitImage);
      }
    }

    // Priorite 2: photo par categorie de titre.
    if (str_contains($title, 'marketing')) {
      $marketingImage = $findOfferImage('marketing');
      if ($marketingImage !== null) {
        return $toOfferImageUrl($marketingImage);
      }
    }
    if (str_contains($title, 'ux') || str_contains($title, 'ui') || str_contains($title, 'design')) {
      $designImage = $findOfferImage('design');
      if ($designImage !== null) {
        return $toOfferImageUrl($designImage);
      }
    }
    if (str_contains($title, 'data') || str_contains($title, 'bi')) {
      $dataImage = $findOfferImage('data');
      if ($dataImage !== null) {
        return $toOfferImageUrl($dataImage);
      }
    }
    if (str_contains($title, 'front')) {
      $frontendImage = $findOfferImage('devfontend');
      if ($frontendImage !== null) {
        return $toOfferImageUrl($frontendImage);
      }
    }
    if (str_contains($title, 'php')) {
      $phpImage = $findOfferImage('devphp');
      if ($phpImage !== null) {
        return $toOfferImageUrl($phpImage);
      }
    }
    if (str_contains($title, 'developpeur') || str_contains($title, 'développeur') || str_contains($title, 'web')) {
      $webImage = $findOfferImage('devweb');
      if ($webImage !== null) {
        return $toOfferImageUrl($webImage);
      }
    }

    return $defaultOfferImageUrl;
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
  <form class="search-grid">
    <div>
      <label class="field-label" for="mot-cle-liste">Mot-clé</label>
      <input
        type="text"
        id="mot-cle-liste"
        class="field-input"
        placeholder="Ex : développeur, UX, marketing..."
      />
    </div>
    <div>
      <label class="field-label" for="ville-liste">Ville</label>
      <input
        type="text"
        id="ville-liste"
        class="field-input"
        placeholder="Ex : Bordeaux, Toulouse..."
      />
    </div>
    <div>
      <label class="field-label" for="competence-liste">Compétence</label>
      <select id="competence-liste" class="field-select">
        <option value="">Toutes</option>
        <option value="dev">Développement</option>
        <option value="design">UX / UI</option>
        <option value="marketing">Marketing</option>
      </select>
    </div>
    <div>
      <label class="field-label" for="duree">Durée</label>
      <select id="duree" class="field-select">
        <option value="">Toutes durées</option>
        <option value="2-3">2-3 mois</option>
        <option value="4-6">4-6 mois</option>
        <option value="6-plus">6 mois et +</option>
      </select>
    </div>
  </form>
</section>

<section class="section" aria-label="Liste d'offres">
  <div class="offers-grid">
    <?php foreach ($offers as $offer): ?>
      <article class="offer-card offer-card--with-thumb">
        <div class="offer-cover offer-cover--thumb">
          <img
            src="<?= htmlspecialchars($resolveOfferImage($offer), ENT_QUOTES) ?>"
            alt="Illustration de l'offre <?= htmlspecialchars((string) ($offer['title'] ?? ''), ENT_QUOTES) ?>"
            loading="lazy"
            onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultOfferImageUrl, ENT_QUOTES) ?>';"
          />
        </div>
        <header class="offer-card-header offer-card-header--thumb">
          <div class="offer-badge"><?= htmlspecialchars($offer['badge'], ENT_QUOTES) ?></div>
          <div>
            <h2 class="offer-title">
              <?= htmlspecialchars($offer['title'], ENT_QUOTES) ?>
            </h2>
            <p class="offer-company">
              <?= htmlspecialchars($offer['company'], ENT_QUOTES) ?>
            </p>
          </div>
        </header>
        <div class="offer-meta">
          <span><?= htmlspecialchars($offer['duration'], ENT_QUOTES) ?></span>
          <span><?= htmlspecialchars($offer['salary'], ENT_QUOTES) ?></span>
          <span>Publié le <?= htmlspecialchars($offer['published'], ENT_QUOTES) ?></span>
        </div>
        <div class="offer-skills">
          <?php foreach ($offer['skills'] as $skill): ?>
            <span class="tag"><?= htmlspecialchars($skill, ENT_QUOTES) ?></span>
          <?php endforeach; ?>
        </div>
        <footer class="offer-footer">
          <div class="offer-tagline">
            <?= htmlspecialchars($offer['tagline'], ENT_QUOTES) ?>
          </div>
          <div class="offer-actions">
            <button
              type="button"
              class="btn-icon btn-icon--wish"
              aria-label="Ajouter aux favoris"
            >
              ♥
            </button>
            <a href="#" class="btn btn-outline" style="padding-inline: 0.9rem">
              Détails
            </a>
          </div>
        </footer>
      </article>
    <?php endforeach; ?>
  </div>

  <?php if (($totalPages ?? 1) > 1): ?>
    <nav class="pagination" aria-label="Pagination des offres">
      <?php if (($currentPage ?? 1) > 1): ?>
        <a class="page-btn" href="<?= $offersPageUrl(((int) $currentPage) - 1) ?>">«</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">«</span>
      <?php endif; ?>

      <?php for ($page = 1; $page <= (int) ($totalPages ?? 1); $page++): ?>
        <a
          class="page-btn<?= $page === (int) ($currentPage ?? 1) ? ' page-btn--active' : '' ?>"
          href="<?= $offersPageUrl($page) ?>"
        >
          <?= $page ?>
        </a>
      <?php endfor; ?>

      <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
        <a class="page-btn" href="<?= $offersPageUrl(((int) $currentPage) + 1) ?>">»</a>
      <?php else: ?>
        <span class="page-btn page-btn--disabled">»</span>
      <?php endif; ?>
    </nav>
  <?php endif; ?>
</section>

