<section class="auth-wrapper">
  <section class="auth-card" aria-labelledby="titre-login">
    <h1 class="auth-title" id="titre-login">Connexion</h1>
    <p class="auth-subtitle">
      Connectez-vous avec l'adresse e-mail associee a votre compte.
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

    <form method="post" action="login">
      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="prenom.nom@viacesi.fr"
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
    </form>
  </section>
</section>
