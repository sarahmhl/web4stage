<?php

// Layout principal commun a l'ensemble des pages du site.
use Core\Flash;
use Core\Security;
use Core\Url;

$routeUrl = static function (string $path = ''): string {
    return htmlspecialchars(Url::route($path), ENT_QUOTES);
};

$assetUrl = static function (string $path): string {
    return htmlspecialchars(Url::asset($path), ENT_QUOTES);
};

$currentPath = Url::currentPath();
$isEntryPage = (bool) ($isEntryPage ?? false);
$isHome = $currentPath === '/accueil' || $currentPath === '/';
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

            <?php // Bouton burger affiche quand la navigation desktop disparait en mobile. ?>
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
          </div>
        </div>

        <?php // Panneau de navigation mobile ouvert et ferme par le bouton burger. ?>
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
        </div>
      </header>

      <main class="app-shell">
        <?php if ($flashMessages !== []): ?>
          <section class="flash-stack" aria-label="Messages système">
            <?php foreach ($flashMessages as $flash): ?>
              <div class="flash flash--<?= htmlspecialchars((string) $flash['type'], ENT_QUOTES) ?>">
                <?= htmlspecialchars((string) $flash['message'], ENT_QUOTES) ?>
              </div>
            <?php endforeach; ?>
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
          </div>
        </div>
      </footer>
    <?php else: ?>
      <?= $pageContent ?>
    <?php endif; ?>

    <script src="<?= $assetUrl('assets/js/main.js') ?>"></script>
  </body>
</html>
