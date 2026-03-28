<?php ?>
<section class="auth-wrapper">
  <section class="auth-card" aria-labelledby="titre-login">
    <h1 class="auth-title" id="titre-login">Se connecter</h1>
    <p class="auth-subtitle">
      Connectez-vous avec l&apos;adresse e-mail associ&eacute;e &agrave; votre compte.
    </p>

    <?php if (!empty($authPrompt)): ?>
      <p class="auth-hint">
        <?= htmlspecialchars((string) $authPrompt, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <p class="auth-hint auth-hint--success">
        <?= htmlspecialchars((string) $success, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <p class="auth-hint auth-hint--error">
        <?= htmlspecialchars((string) $error, ENT_QUOTES) ?>
      </p>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('login'), ENT_QUOTES) ?>" data-js-validate>
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
      <input type="hidden" name="redirect_to" value="<?= htmlspecialchars((string) ($redirectTo ?? ''), ENT_QUOTES) ?>" />
      <input type="hidden" name="intent" value="<?= htmlspecialchars((string) ($intent ?? ''), ENT_QUOTES) ?>" />

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

    <div class="home-summary-list">
      <a href="<?= htmlspecialchars(\Core\Url::route('accueil'), ENT_QUOTES) ?>" class="home-summary-item">
        <strong>Retour &agrave; l&apos;accueil</strong>
        <span>Continuer &agrave; explorer le portail public.</span>
      </a>
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="home-summary-item">
        <strong>Voir les offres</strong>
        <span>Consulter les stages avant de vous connecter.</span>
      </a>
    </div>
  </section>
</section>
