<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Fiche entreprise</span>
    <h1 class="page-heading-title"><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      Consultez les coordonnées, les avis étudiants et les offres de stage associées.
    </p>
  </div>
</header>

<<<<<<< HEAD
use Core\Flash;
use Core\Security;
use Core\Url;
=======
<section class="page-layout detail-layout">
  <article class="detail-card">
    <div class="detail-content detail-content--full">
      <div class="detail-stats">
        <div class="stat-pill">
          <span>Ville</span>
          <strong><?= htmlspecialchars((string) ($company['ville'] ?: 'Non précisée'), ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Secteur</span>
          <strong><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Offres liées</span>
          <strong><?= (int) ($company['offers_count'] ?? 0) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Note moyenne</span>
          <strong><?= $company['average_rating'] !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . ' / 5' : 'Aucune note' ?></strong>
        </div>
      </div>
>>>>>>> 5857f745db1e52a2bfb060eecc3d341ea919b5e2

      <section class="detail-section">
        <h2>Présentation</h2>
        <p><?= nl2br(htmlspecialchars((string) ($company['description'] ?: 'Aucune description disponible pour le moment.'), ENT_QUOTES)) ?></p>
      </section>

<<<<<<< HEAD
$assetUrl = static function (string $path): string {
    return htmlspecialchars(Url::asset($path), ENT_QUOTES);
};

$currentPath = Url::currentPath();
$isEntryPage = (bool) ($isEntryPage ?? false);
$isHome = $currentPath === '/accueil';
$isLoggedIn = \Core\Auth::check();
$user = \Core\Auth::user();
$userRole = $user['role'] ?? '';
$logoutCsrfToken = $isLoggedIn ? Security::generateCsrfToken() : null;
$flashMessages = Flash::consume();
$dashboardPath = null;
$dashboardLabel = null;

if ($userRole === \Core\Auth::ROLE_ETUDIANT) {
    $dashboardPath = '/dashboard-etudiant';
    $dashboardLabel = 'Espace étudiant';
} elseif ($userRole === \Core\Auth::ROLE_PILOTE) {
    $dashboardPath = '/dashboard-pilote';
    $dashboardLabel = 'Espace pilote';
} elseif ($userRole === \Core\Auth::ROLE_ADMIN) {
    $dashboardPath = '/dashboard-admin';
    $dashboardLabel = 'Administration';
}

$bodyClasses = [];
$workspaceFlatPrefixes = [
    '/dashboard-etudiant',
    '/dashboard-pilote',
    '/dashboard-admin',
    '/etudiant',
    '/pilote',
    '/admin',
    '/candidatures',
    '/wishlist',
];
$isWorkspaceFlat = false;

foreach ($workspaceFlatPrefixes as $prefix) {
    if ($currentPath === $prefix || str_starts_with($currentPath, $prefix . '/')) {
        $isWorkspaceFlat = true;
        break;
    }
}

if ($isHome) {
    $bodyClasses[] = 'home-page';
}
if ($isEntryPage) {
    $bodyClasses[] = 'entry-page';
}
if ($isWorkspaceFlat) {
    $bodyClasses[] = 'workspace-flat';
}

$metaDescription = htmlspecialchars(
    (string) ($metaDescription ?? 'Plateforme professionnelle pour gérer les offres de stage, les candidatures et le suivi étudiant.'),
    ENT_QUOTES
);
$metaKeywords = htmlspecialchars(
    (string) ($metaKeywords ?? 'stages, offres, candidatures, cesi, web4stage, entreprise, étudiant'),
    ENT_QUOTES
);
$canonicalTarget = $currentPath === '/' ? '' : ltrim($currentPath, '/');
$queryString = trim((string) ($_SERVER['QUERY_STRING'] ?? ''));
if ($queryString !== '') {
    $canonicalTarget .= ($canonicalTarget === '' ? '?' : '&') . $queryString;
}
$canonicalUrl = htmlspecialchars(Url::absolute($canonicalTarget), ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($title) ? htmlspecialchars((string) $title, ENT_QUOTES) : 'Web4Stage' ?></title>
    <meta name="description" content="<?= $metaDescription ?>" />
    <meta name="keywords" content="<?= $metaKeywords ?>" />
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="<?= $canonicalUrl ?>" />
    <link rel="icon" type="image/png" href="<?= $assetUrl('assets/img/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= $assetUrl('assets/css/style.css') ?>" />
  </head>
  <body class="<?= htmlspecialchars(trim(implode(' ', $bodyClasses)), ENT_QUOTES) ?>">
    <?php if (!$isEntryPage): ?>
      <header class="navbar">
        <div class="navbar-inner">
          <a href="<?= $routeUrl('accueil') ?>" class="brand">
            <div class="brand-text">
              <span class="brand-title" data-logo="Web4Stage">Web<span class="brand-four">4</span>Stage</span>
              <span class="brand-subtitle">Stages &amp; candidatures</span>
            </div>
          </a>

          <nav class="nav-links" aria-label="Navigation principale">
            <a href="<?= $routeUrl('accueil') ?>" class="nav-link<?= $isHome ? ' nav-link--active' : '' ?>">Accueil</a>
            <a href="<?= $routeUrl('offres') ?>" class="nav-link<?= str_starts_with($currentPath, '/offres') ? ' nav-link--active' : '' ?>">Offres de stage</a>
            <?php if ($dashboardPath !== null && $dashboardLabel !== null): ?>
              <a href="<?= $routeUrl(ltrim($dashboardPath, '/')) ?>" class="nav-link<?= $currentPath === $dashboardPath ? ' nav-link--active' : '' ?>"><?= htmlspecialchars($dashboardLabel, ENT_QUOTES) ?></a>
            <?php endif; ?>
          </nav>

          <div class="nav-cta">
            <?php if (!$isLoggedIn): ?>
              <a href="<?= $routeUrl('login') ?>" class="btn btn-outline">Se connecter</a>
            <?php else: ?>
              <form method="post" action="<?= $routeUrl('logout') ?>" class="nav-logout-form">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $logoutCsrfToken, ENT_QUOTES) ?>" />
                <button type="submit" class="btn btn-primary">Se déconnecter</button>
              </form>
            <?php endif; ?>
            <button
              type="button"
              class="burger"
              aria-label="Ouvrir le menu mobile"
              aria-expanded="false"
              aria-controls="nav-mobile-menu"
            >
              <span></span>
              <span></span>
              <span></span>
            </button>
=======
      <section class="detail-section">
        <h2>Coordonnées</h2>
        <div class="detail-meta-grid">
          <div>
            <span class="detail-label">Email</span>
            <?php if (!empty($company['email_contact'])): ?>
              <a class="detail-link" href="mailto:<?= htmlspecialchars((string) $company['email_contact'], ENT_QUOTES) ?>">
                <strong class="detail-value"><?= htmlspecialchars((string) $company['email_contact'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseigné</strong>
            <?php endif; ?>
          </div>
          <div>
            <span class="detail-label">Téléphone</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($company['telephone_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </div>
          <div>
            <span class="detail-label">Site</span>
            <?php if (!empty($company['site_web'])): ?>
              <a
                class="detail-link"
                href="<?= htmlspecialchars((string) $company['site_web'], ENT_QUOTES) ?>"
                target="_blank"
                rel="noreferrer"
              >
                <strong class="detail-value"><?= htmlspecialchars((string) $company['site_web'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseigné</strong>
            <?php endif; ?>
>>>>>>> 5857f745db1e52a2bfb060eecc3d341ea919b5e2
          </div>
        </div>
      </section>

<<<<<<< HEAD
        <div id="nav-mobile-menu" class="nav-mobile" aria-label="Navigation mobile">
          <a href="<?= $routeUrl('accueil') ?>" class="nav-link<?= $isHome ? ' nav-link--active' : '' ?>">Accueil</a>
          <a href="<?= $routeUrl('offres') ?>" class="nav-link<?= str_starts_with($currentPath, '/offres') ? ' nav-link--active' : '' ?>">Offres de stage</a>
          <?php if ($dashboardPath !== null && $dashboardLabel !== null): ?>
            <a href="<?= $routeUrl(ltrim($dashboardPath, '/')) ?>" class="nav-link<?= $currentPath === $dashboardPath ? ' nav-link--active' : '' ?>"><?= htmlspecialchars($dashboardLabel, ENT_QUOTES) ?></a>
          <?php endif; ?>
          <?php if (!$isLoggedIn): ?>
            <a href="<?= $routeUrl('login') ?>" class="nav-link<?= str_starts_with($currentPath, '/login') ? ' nav-link--active' : '' ?>">Se connecter</a>
          <?php else: ?>
            <form method="post" action="<?= $routeUrl('logout') ?>" class="nav-mobile-logout">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $logoutCsrfToken, ENT_QUOTES) ?>" />
              <button type="submit" class="nav-link nav-link--button">Se déconnecter</button>
            </form>
          <?php endif; ?>
=======
      <?php if (!empty($canReview)): ?>
        <div class="detail-actions">
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-primary">
            Évaluer cette entreprise
          </a>
>>>>>>> 5857f745db1e52a2bfb060eecc3d341ea919b5e2
        </div>
      <?php endif; ?>
    </div>
  </article>

  <aside class="side-card">
    <h2 class="side-card-title">Actions liées</h2>
    <p class="side-card-text">
      Explorez les offres de cette entreprise ou ajoutez un retour étudiant.
    </p>
    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Retour aux offres</strong>
        <span>Continuer la recherche</span>
      </a>
      <?php if (!empty($canReview)): ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="management-offer-link">
          <strong>Laisser une évaluation</strong>
          <span>Partager votre retour sur l’entreprise</span>
        </a>
      <?php endif; ?>
    </div>
  </aside>
</section>

<?php if (!empty($company['offers'])): ?>
  <section class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Offres liées</h2>
        <p class="section-subtitle">Stages actuellement proposés par cette entreprise.</p>
      </div>
    </div>

    <div class="offers-grid">
      <?php foreach ($company['offers'] as $offer): ?>
        <article class="offer-card">
          <header class="offer-card-header">
            <div class="offer-badge"><?= htmlspecialchars((string) $offer['badge'], ENT_QUOTES) ?></div>
            <div>
              <h3 class="offer-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h3>
              <p class="offer-company"><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></p>
            </div>
          </header>
          <div class="offer-meta">
            <span><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
            <span><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></span>
          </div>
          <div class="offer-skills">
            <?php foreach (($offer['skills'] ?? []) as $skill): ?>
              <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
<<<<<<< HEAD
          </section>
        <?php endif; ?>

        <?= $pageContent ?>
      </main>

      <footer class="footer">
        <div class="footer-inner">
          <span>&copy; 2026 &middot; Web4Stage &middot; Projet pédagogique CESI</span>
          <div class="footer-links">
            <a href="<?= $routeUrl('mentions-legales') ?>">Mentions légales</a>
            <a href="<?= $routeUrl('politique-confidentialite') ?>">Politique de confidentialité</a>
=======
>>>>>>> 5857f745db1e52a2bfb060eecc3d341ea919b5e2
          </div>
          <footer class="offer-footer">
            <div class="offer-tagline"><?= htmlspecialchars((string) $offer['tagline'], ENT_QUOTES) ?></div>
            <div class="offer-actions">
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">Voir</a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Avis étudiants</h2>
      <p class="section-subtitle">Retours déjà publiés sur cette entreprise.</p>
    </div>
  </div>

  <?php if (!empty($company['reviews'])): ?>
    <div class="dashboard-grid">
      <?php foreach ($company['reviews'] as $review): ?>
        <article class="dash-card">
          <header class="dash-card-header">
            <span class="dash-card-title"><?= htmlspecialchars(trim((string) $review['prenom'] . ' ' . (string) $review['nom']), ENT_QUOTES) ?></span>
            <span class="pill-small"><?= (int) ($review['note'] ?? 0) ?>/5</span>
          </header>
          <p class="action-card-text"><?= nl2br(htmlspecialchars((string) $review['commentaire'], ENT_QUOTES)) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="dash-card">
      <p class="action-card-text">Aucun avis n’a encore été publié pour cette entreprise.</p>
    </div>
  <?php endif; ?>
</section>

