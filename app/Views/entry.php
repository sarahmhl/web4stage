<?php
  // Vue d entree du site avec le premier contact visuel et l acces a la connexion.
  $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
  $routePrefix = rtrim($scriptName, '/');
  $routeUrl = static function (string $path) use ($routePrefix): string {
    return htmlspecialchars(str_replace(' ', '%20', $routePrefix . '/' . ltrim($path, '/')), ENT_QUOTES);
  };
?>
<section class="entry-premium" aria-labelledby="entry-title">
  <div class="entry-premium-glow entry-premium-glow--one" aria-hidden="true"></div>
  <div class="entry-premium-glow entry-premium-glow--two" aria-hidden="true"></div>

  <div class="entry-premium-card entry-premium-card--compact tilt-3d">
    <div class="entry-logo" aria-label="Web4Stage" data-logo="Web4Stage">Web<span>4</span>Stage</div>
    <p class="entry-premium-kicker">Stages &amp; candidatures</p>
    <h1 id="entry-title" class="entry-premium-title">Connectez-vous</h1>

    <?php if (!empty($success)): ?>
      <p class="auth-hint auth-hint--success entry-login-feedback">
        <?= htmlspecialchars($success, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <p class="auth-hint auth-hint--error entry-login-feedback">
        <?= htmlspecialchars($error, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <form method="post" action="<?= $routeUrl('login') ?>" class="entry-login-form">
      <input type="hidden" name="return_to" value="entry" />

      <div class="form-group">
        <label for="entry-email">Adresse e-mail</label>
        <input
          type="email"
          id="entry-email"
          name="email"
          class="form-control"
          placeholder="prenom.nom@viacesi.fr"
          required
        />
      </div>

      <div class="form-group">
        <label for="entry-password">Mot de passe</label>
        <input
          type="password"
          id="entry-password"
          name="password"
          class="form-control"
          placeholder="Votre mot de passe"
          required
        />
      </div>

      <div class="entry-premium-actions">
        <button type="submit" class="btn btn-primary entry-premium-cta">Se connecter</button>
      </div>
    </form>
  </div>
</section>
