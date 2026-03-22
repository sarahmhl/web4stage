<section class="auth-wrapper">
  <section class="auth-card" aria-labelledby="titre-register">
    <h1 class="auth-title" id="titre-register">Créer un compte étudiant</h1>
    <p class="auth-subtitle">
      Inscription réservée aux étudiants avec une adresse e-mail @viacesi.fr.
    </p>

    <?php if (!empty($error)): ?>
      <p class="auth-hint" style="color:#c0392b; font-weight:600;">
        <?= htmlspecialchars((string) $error, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <form method="post" action="index.php/register">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />

      <div class="form-group">
        <label for="email">Adresse e-mail institutionnelle</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="prenom.nom@viacesi.fr"
          value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES) ?>"
          pattern="^[A-Za-z0-9._%+-]+@viacesi\.fr$"
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
          placeholder="8 caractères minimum"
          minlength="8"
          required
        />
      </div>

      <div class="form-group">
        <label for="password_confirm">Confirmer le mot de passe</label>
        <input
          type="password"
          id="password_confirm"
          name="password_confirm"
          class="form-control"
          placeholder="Retapez le mot de passe"
          minlength="8"
          required
        />
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary">Créer mon compte</button>
        <a href="index.php/login" class="link-soft">J'ai déjà un compte</a>
      </div>
    </form>
  </section>
</section>
