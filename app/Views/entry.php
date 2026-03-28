<?php
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
    <h1 id="entry-title" class="entry-premium-title">Se connecter</h1>

    <?php if (!empty($authPrompt)): ?>
      <p class="auth-hint entry-login-feedback">
        <?= htmlspecialchars((string) $authPrompt, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <p class="auth-hint auth-hint--success entry-login-feedback">
        <?= htmlspecialchars((string) $success, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <p class="auth-hint auth-hint--error entry-login-feedback">
        <?= htmlspecialchars((string) $error, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <form method="post" action="<?= $routeUrl('login') ?>" class="entry-login-form" data-js-validate>
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
      <?php if (!empty($returnTo)): ?>
        <input type="hidden" name="return_to" value="<?= htmlspecialchars((string) $returnTo, ENT_QUOTES) ?>" />
      <?php endif; ?>
      <input type="hidden" name="redirect_to" value="<?= htmlspecialchars((string) ($redirectTo ?? ''), ENT_QUOTES) ?>" />
      <input type="hidden" name="intent" value="<?= htmlspecialchars((string) ($intent ?? ''), ENT_QUOTES) ?>" />

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
