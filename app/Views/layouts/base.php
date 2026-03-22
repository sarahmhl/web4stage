<?php
  // Layout principal partage par les pages internes : navigation, flash messages, contenu et footer.
  use Core\Flash;
  use Core\Url;

  $routeUrl = static function (string $path = ''): string {
    return htmlspecialchars(Url::route($path), ENT_QUOTES);
  };

  $assetUrl = static function (string $path): string {
    return htmlspecialchars(Url::asset($path), ENT_QUOTES);
  };

  $currentPath = Url::currentPath();
  $isEntryPage = (bool) ($isEntryPage ?? false);
  $isHome = $currentPath === '/accueil';
  $isLoggedIn = \Core\Auth::check();
  $user = \Core\Auth::user();
  $userRole = $user['role'] ?? '';
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
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES) : 'Web4Stage' ?></title>
    <meta
      name="description"
      content="Plateforme professionnelle pour gérer les offres de stage, les candidatures et le suivi étudiant."
    />
    <link rel="icon" type="image/png" href="<?= $assetUrl('assets/img/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= $assetUrl('assets/css/style.css') ?>" />
  </head>
  <body class="<?= implode(' ', $bodyClasses) ?>">
    <?php if (!$isEntryPage): ?>
      <header class="navbar">
        <div class="navbar-inner">
          <a href="<?= $routeUrl('') ?>" class="brand">
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
              <a href="<?= $routeUrl('logout') ?>" class="btn btn-primary">Se déconnecter</a>
            <?php endif; ?>
            <button class="burger" aria-label="Ouvrir le menu mobile">
              <span></span>
              <span></span>
              <span></span>
            </button>
          </div>
        </div>
        <div class="nav-mobile" aria-label="Navigation mobile">
          <a href="<?= $routeUrl('accueil') ?>" class="nav-link<?= $isHome ? ' nav-link--active' : '' ?>">Accueil</a>
          <a href="<?= $routeUrl('offres') ?>" class="nav-link<?= str_starts_with($currentPath, '/offres') ? ' nav-link--active' : '' ?>">Offres de stage</a>
          <?php if ($dashboardPath !== null && $dashboardLabel !== null): ?>
            <a href="<?= $routeUrl(ltrim($dashboardPath, '/')) ?>" class="nav-link<?= $currentPath === $dashboardPath ? ' nav-link--active' : '' ?>"><?= htmlspecialchars($dashboardLabel, ENT_QUOTES) ?></a>
          <?php endif; ?>
          <?php if (!$isLoggedIn): ?>
            <a href="<?= $routeUrl('login') ?>" class="nav-link<?= str_starts_with($currentPath, '/login') ? ' nav-link--active' : '' ?>">Se connecter</a>
          <?php else: ?>
            <a href="<?= $routeUrl('logout') ?>" class="nav-link">Se déconnecter</a>
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
          <span>© 2026 · Web4Stage · Projet pédagogique CESI</span>
          <div class="footer-links">
            <a href="<?= $routeUrl('mentions-legales') ?>">Mentions légales</a>
            <a href="#">Politique de confidentialité</a>
          </div>
        </div>
      </footer>
    <?php else: ?>
      <?= $pageContent ?>
    <?php endif; ?>

    <script src="<?= $assetUrl('assets/js/main.js') ?>"></script>
  </body>
</html>
