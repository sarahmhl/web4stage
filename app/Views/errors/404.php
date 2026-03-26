<?php ?>
<section class="empty-state">
  <span class="pill-small">Erreur 404</span>
  <h1 class="empty-state-title">La page demandée est introuvable</h1>
  <p class="empty-state-text">
    Le lien est peut-être incomplet, ou la ressource n’existe plus.
  </p>
  <div class="empty-state-actions">
    <a href="<?= htmlspecialchars(\Core\Url::route('accueil'), ENT_QUOTES) ?>" class="btn btn-primary">Retour à l’accueil</a>
    <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-outline">Voir les offres</a>
  </div>
</section>
