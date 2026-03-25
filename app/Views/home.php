<?php
  // Vue de la page d accueil publique avec hero, offres mises en avant et avis etudiants.
  $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
  $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
  $projectBase = $baseDir === '' ? '' : rtrim(str_replace('\\', '/', dirname($baseDir)), '/');
  $assetBase = str_replace(' ', '%20', $projectBase);
  $basePath = '/assets/img/offers/';
  $offersDir = dirname(__DIR__, 2) . '/assets/img/offers';
  if (!is_dir($offersDir)) {
    $offersDir = dirname(__DIR__, 3) . '/assets/img/offers';
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
  $isLoggedIn = \Core\Auth::check();

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
<section class="hero hero--compact" aria-labelledby="titre-hero">
  <div class="hero-copy">
    <div class="hero-badge">
      <span class="hero-badge-dot"></span>
      <span>Plateforme de suivi des stages</span>
    </div>
    <h1 class="hero-title" id="titre-hero">
      Trouvez votre stage
      <span class="hero-title-accent">plus facilement</span>
    </h1>
    <p class="hero-subtitle">
      Web4Stage rassemble les offres, les candidatures et le suivi dans une interface simple et plus claire.
    </p>
    <div class="hero-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">
        Consulter les offres
      </a>
      <?php if (!$isLoggedIn): ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('entry'), ENT_QUOTES) ?>" class="btn btn-outline">
          Se connecter
        </a>
      <?php endif; ?>
    </div>
    <div class="hero-notes">
      <div class="hero-kpi">
        <strong><?= (int) ($overviewStats['offers'] ?? 0) ?> offres</strong>
        <span><?= (int) ($overviewStats['cities'] ?? 0) ?> villes et plusieurs filtres actifs</span>
      </div>
      <div class="hero-kpi">
        <strong><?= (int) ($overviewStats['companies'] ?? 0) ?> entreprises</strong>
        <span><?= (int) ($overviewStats['skills'] ?? 0) ?> competences deja referencees</span>
      </div>
    </div>
  </div>

  <aside class="hero-card hero-card--summary" aria-labelledby="titre-apercu-plateforme">
    <div class="hero-card-inner">
      <div class="hero-card-title">
        <span class="pill">Acces rapides</span>
        <div class="hero-card-logo">Go</div>
      </div>
      <h2 class="hero-login-title" id="titre-apercu-plateforme">Un accueil plus simple</h2>
      <p class="hero-login-copy">
        Retrouvez rapidement les principales entrees du site sans surcharger la page d'accueil.
      </p>

      <div class="home-summary-list">
        <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="home-summary-item">
          <strong>Parcourir les offres</strong>
          <span>Consulter rapidement les stages disponibles.</span>
        </a>
        <?php if (!$isLoggedIn): ?>
          <a href="<?= htmlspecialchars(\Core\Url::route('entry'), ENT_QUOTES) ?>" class="home-summary-item">
            <strong>Se connecter</strong>
            <span>Acceder directement a son espace personnel.</span>
          </a>
        <?php endif; ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('mentions-legales'), ENT_QUOTES) ?>" class="home-summary-item">
          <strong>Infos du projet</strong>
          <span>Voir le cadre pedagogique et les mentions legales.</span>
        </a>
      </div>
    </div>
  </aside>
</section>

<?php if (!empty($studentReviews) && is_array($studentReviews)): ?>
  <section class="section section--compact" aria-labelledby="section-avis-etudiants">
    <div class="section-header">
      <div>
        <h2 class="section-title" id="section-avis-etudiants">Avis des etudiants</h2>
        <p class="section-subtitle">
          Retours deja postes apres candidature ou debut de stage.
        </p>
      </div>
    </div>

    <div class="testimonials-grid" aria-label="Avis etudiants deja publies">
      <?php foreach ($studentReviews as $review): ?>
        <article class="testimonial-card">
          <div class="testimonial-head">
            <span class="testimonial-avatar">
              <?= htmlspecialchars((string) ($review['initials'] ?? ''), ENT_QUOTES) ?>
            </span>
            <div>
              <h3 class="testimonial-name">
                <?= htmlspecialchars((string) ($review['name'] ?? ''), ENT_QUOTES) ?>
              </h3>
              <p class="testimonial-role">
                <?= htmlspecialchars((string) ($review['role'] ?? ''), ENT_QUOTES) ?>
              </p>
            </div>
            <div class="testimonial-rating" aria-label="Note etudiante">
              <?php for ($i = 0; $i < (int) ($review['rating'] ?? 0); $i++): ?>
                <span>&#9733;</span>
              <?php endfor; ?>
            </div>
          </div>

          <p class="testimonial-text">
            <?= htmlspecialchars((string) ($review['text'] ?? ''), ENT_QUOTES) ?>
          </p>

          <p class="testimonial-date">
            <?= htmlspecialchars((string) ($review['date'] ?? ''), ENT_QUOTES) ?>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<section class="search-section search-section--compact" aria-label="Recherche rapide">
  <form method="get" action="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="search-grid">
    <div>
      <label class="field-label" for="mot-cle">Mot-clé</label>
      <input
        type="text"
        id="mot-cle"
        name="keyword"
        class="field-input"
        placeholder="Ex : développeur front, marketing..."
      />
    </div>
    <div>
      <label class="field-label" for="ville">Ville</label>
      <input
        type="text"
        id="ville"
        name="city"
        class="field-input"
        placeholder="Ex : Paris, Lyon..."
      />
    </div>
    <div>
      <label class="field-label" for="competence">Compétence principale</label>
      <select id="competence" name="skill" class="field-select">
        <option value="">Toutes les compétences</option>
        <option value="dev-web">Développement Web</option>
        <option value="data">Data / BI</option>
        <option value="marketing">Marketing digital</option>
        <option value="reseau">Systèmes &amp; Réseaux</option>
      </select>
    </div>
    <div class="search-actions">
      <button type="submit" class="btn btn-primary btn-full">Lancer la recherche</button>
    </div>
  </form>
</section>

<section class="section section--compact" aria-labelledby="section-offres-populaires">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-offres-populaires">Offres populaires</h2>
      <p class="section-subtitle">
        Une sélection directe des stages les plus consultés.
      </p>
    </div>
    <div class="section-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="link-soft">Voir toutes les offres -></a>
    </div>
  </div>

  <div class="offers-grid" aria-label="Liste d'offres populaires">
    <?php foreach ($popularOffers as $offer): ?>
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
            <h3 class="offer-title">
              <?= htmlspecialchars($offer['title'], ENT_QUOTES) ?>
            </h3>
            <p class="offer-company">
              <?= htmlspecialchars($offer['company'], ENT_QUOTES) ?>
            </p>
          </div>
        </header>
        <div class="offer-meta">
          <span><?= htmlspecialchars($offer['duration'], ENT_QUOTES) ?></span>
          <span><?= htmlspecialchars($offer['salary'], ENT_QUOTES) ?></span>
          <span>Début : <?= htmlspecialchars($offer['start'], ENT_QUOTES) ?></span>
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
            <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) ($offer['id'] ?? 0)), ENT_QUOTES) ?>" class="btn btn-outline" style="padding-inline: 0.9rem">
              Voir
            </a>
          </div>
        </footer>
      </article>
    <?php endforeach; ?>
  </div>
</section>

