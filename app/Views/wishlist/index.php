<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">&#9829; Wish-list</span>
    <h1 class="page-heading-title">Mes offres favorites</h1>
    <p class="page-heading-subtitle">
      Retrouvez les offres enregistrées, retirez-les ou ouvrez leur fiche détaillée.
    </p>
  </div>
</header>

<?php if (!empty($items)): ?>
  <section class="offers-grid">
    <?php foreach ($items as $offer): ?>
      <article class="offer-card">
        <header class="offer-card-header">
          <div class="offer-badge"><?= htmlspecialchars((string) $offer['badge'], ENT_QUOTES) ?></div>
          <div>
            <h2 class="offer-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h2>
            <p class="offer-company"><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></p>
          </div>
        </header>
        <div class="offer-meta">
          <span><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
          <span><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></span>
          <span><?= htmlspecialchars((string) $offer['city'], ENT_QUOTES) ?></span>
        </div>
        <div class="offer-skills">
          <?php foreach (($offer['skills'] ?? []) as $skill): ?>
            <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
          <?php endforeach; ?>
        </div>
        <footer class="offer-footer">
          <div class="offer-tagline"><?= htmlspecialchars((string) $offer['tagline'], ENT_QUOTES) ?></div>
          <div class="offer-actions">
            <form method="post" action="<?= htmlspecialchars(\Core\Url::route('wishlist/toggle'), ENT_QUOTES) ?>">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
              <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
              <input type="hidden" name="redirect_to" value="wishlist" />
              <button type="submit" class="btn btn-outline">Retirer</button>
            </form>
            <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-primary">Voir</a>
          </div>
        </footer>
      </article>
    <?php endforeach; ?>
  </section>
<?php else: ?>
  <section class="empty-state">
    <span class="pill-small">Aucune offre</span>
    <h1 class="empty-state-title">Votre wish-list est vide</h1>
    <p class="empty-state-text">Ajoutez des offres depuis le catalogue pour les retrouver ici.</p>
    <div class="empty-state-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les offres</a>
    </div>
  </section>
<?php endif; ?>

