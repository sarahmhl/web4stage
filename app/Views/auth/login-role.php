<section class="auth-wrapper">
  <section class="auth-card" aria-labelledby="titre-login-role">
    <a href="<?= htmlspecialchars($backUrl, ENT_QUOTES) ?>" class="link-soft">Retour au choix des profils</a>

    <div class="role-login-intro">
      <span class="pill"><?= htmlspecialchars($profile['badge'], ENT_QUOTES) ?></span>
      <span class="role-login-key"><?= htmlspecialchars($profile['label'], ENT_QUOTES) ?></span>
    </div>

    <h1 class="auth-title" id="titre-login-role">Connexion <?= htmlspecialchars($profile['label'], ENT_QUOTES) ?></h1>
    <p class="auth-subtitle">
      <?= htmlspecialchars($profile['description'], ENT_QUOTES) ?>
    </p>

    <?php if (!empty($success)): ?>
      <p class="auth-hint auth-hint--success">
        <?= htmlspecialchars($success, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <p class="auth-hint auth-hint--error">
        <?= htmlspecialchars($error, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES) ?>">
      <input type="hidden" name="return_to" value="<?= htmlspecialchars($profile['key'], ENT_QUOTES) ?>" />

      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="<?= htmlspecialchars($profile['placeholder'], ENT_QUOTES) ?>"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Votre mot de passe"
          required
        />
      </div>

      <div class="form-footer form-footer--stack">
        <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
      </div>

      <p class="auth-hint">
        Format attendu :
        <code><?= htmlspecialchars($profile['emailRule'], ENT_QUOTES) ?></code>
      </p>
      <p class="auth-hint">
        Si vous n'avez pas encore d'accès, contactez un administrateur ou un pilote de promotion.
      </p>
    </form>
  </section>
</section>
