<section class="empty-state">
  <span class="pill-small">Accès refusé</span>
  <h1 class="empty-state-title">Vous n avez pas les droits pour accéder à cette page</h1>
  <p class="empty-state-text">
    Cette interface est réservée à un autre rôle utilisateur.
  </p>
  <div class="empty-state-actions">
    <a href="<?= htmlspecialchars(\Core\Url::route('accueil'), ENT_QUOTES) ?>" class="btn btn-primary">Retour à l accueil</a>
    <a href="<?= htmlspecialchars(\Core\Url::route('login'), ENT_QUOTES) ?>" class="btn btn-outline">Se connecter</a>
  </div>
</section>
