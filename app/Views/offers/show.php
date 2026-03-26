<?php
  $offerImage = \Core\Url::asset('assets/img/offers/' . ((string) ($offer['image'] ?? 'devweb.jpeg')));
  $defaultImage = \Core\Url::asset('assets/img/web4stage.png');
  $applicationsCount = (int) ($offer['applications_count'] ?? 0);
  $wishlistCount = (int) ($offer['wishlist_count'] ?? 0);
  $guestLoginUrl = static function (string $intent) use ($offer): string {
    return htmlspecialchars(
      \Core\Url::route('login') . '?' . http_build_query([
        'redirect' => 'offres/detail?id=' . (int) ($offer['id'] ?? 0),
        'intent' => $intent,
      ]),
      ENT_QUOTES
    );
  };
?>

<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">D&eacute;tail de l'offre</span>
    <h1 class="page-heading-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      L'essentiel de l'offre, de l'entreprise et des actions disponibles sur une seule page plus lisible.
    </p>
  </div>
</header>

<section class="page-layout detail-layout">
  <article class="detail-card">
    <div class="detail-cover">
      <img
        src="<?= htmlspecialchars($offerImage, ENT_QUOTES) ?>"
        alt="Illustration de l'offre <?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?>"
        onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultImage, ENT_QUOTES) ?>';"
      />
    </div>

    <div class="detail-content">
      <div class="detail-top-block">
        <?php if (!empty($offer['city'])): ?>
          <div class="detail-header-meta">
            <span class="detail-city-chip"><?= htmlspecialchars((string) $offer['city'], ENT_QUOTES) ?></span>
          </div>
        <?php endif; ?>

        <h2 class="detail-offer-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h2>

        <p class="detail-company">
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $offer['company_id']), ENT_QUOTES) ?>">
            <?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?>
          </a>
        </p>

        <div class="detail-facts" aria-label="Informations clés">
          <span><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
          <span><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></span>
          <span><?= $applicationsCount ?> candidature<?= $applicationsCount > 1 ? 's' : '' ?></span>
          <span><?= $wishlistCount ?> favori<?= $wishlistCount > 1 ? 's' : '' ?></span>
        </div>

        <div class="detail-actions">
          <?php if (\Core\Auth::checkRole(\Core\Auth::ROLE_ETUDIANT)): ?>
            <form method="post" action="<?= htmlspecialchars(\Core\Url::route('wishlist/toggle'), ENT_QUOTES) ?>">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
              <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
              <input type="hidden" name="redirect_to" value="offres/detail?id=<?= (int) $offer['id'] ?>" />
              <button type="submit" class="btn <?= !empty($isWishlisted) ? 'btn-primary' : 'btn-outline' ?>">
                <?= !empty($isWishlisted) ? 'Retirer de la wish-list' : 'Ajouter &agrave; la wish-list' ?>
              </button>
            </form>
          <?php elseif (!\Core\Auth::check()): ?>
            <a href="<?= $guestLoginUrl('wishlist') ?>" class="btn btn-outline">
              Se connecter pour la wish-list
            </a>
          <?php endif; ?>

          <?php if (!empty($canApply)): ?>
            <a href="<?= htmlspecialchars(\Core\Url::route('candidatures/nouvelle?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-primary">
              Postuler
            </a>
          <?php elseif (!\Core\Auth::check()): ?>
            <a href="<?= $guestLoginUrl('apply') ?>" class="btn btn-primary">
              Se connecter pour postuler
            </a>
          <?php endif; ?>
        </div>
      </div>

      <section class="detail-section detail-section-card">
        <h2>Mission</h2>
        <p><?= nl2br(htmlspecialchars((string) ($offer['description'] ?? ''), ENT_QUOTES)) ?></p>
      </section>

      <?php if (!empty($offer['skills'])): ?>
        <section class="detail-section detail-section-card">
          <h2>Comp&eacute;tences cl&eacute;s</h2>
          <div class="offer-skills">
            <?php foreach (($offer['skills'] ?? []) as $skill): ?>
              <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <section class="detail-section detail-section-card detail-section-card--company">
        <h2>Entreprise</h2>
        <div class="detail-meta-grid">
          <div>
            <span class="detail-label">Secteur</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($offer['company_sector'] ?? 'Non renseigné'), ENT_QUOTES) ?></strong>
          </div>
          <div>
            <span class="detail-label">Contact</span>
            <?php if (!empty($offer['company_email'])): ?>
              <a class="detail-link" href="mailto:<?= htmlspecialchars((string) $offer['company_email'], ENT_QUOTES) ?>">
                <strong class="detail-value"><?= htmlspecialchars((string) $offer['company_email'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseign&eacute;</strong>
            <?php endif; ?>
          </div>
          <div>
            <span class="detail-label">T&eacute;l&eacute;phone</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($offer['company_phone'] ?? 'Non renseigné'), ENT_QUOTES) ?></strong>
          </div>
          <div>
            <span class="detail-label">Site</span>
            <?php if (!empty($offer['company_site'])): ?>
              <a
                class="detail-link"
                href="<?= htmlspecialchars((string) $offer['company_site'], ENT_QUOTES) ?>"
                target="_blank"
                rel="noreferrer"
              >
                <strong class="detail-value"><?= htmlspecialchars((string) $offer['company_site'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseign&eacute;</strong>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($offer['company_description'])): ?>
          <p class="detail-company-description">
            <?= nl2br(htmlspecialchars((string) $offer['company_description'], ENT_QUOTES)) ?>
          </p>
        <?php endif; ?>
      </section>
    </div>
  </article>

  <aside class="side-card side-card--detail">
    <h2 class="side-card-title">Navigation</h2>
    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $offer['company_id']), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Voir la fiche entreprise</strong>
        <span>Contact et pr&eacute;sentation</span>
      </a>

      <?php if (!empty($relatedOffers)): ?>
        <a href="#related-offers" class="management-offer-link">
          <strong>Autres offres de l'entreprise</strong>
          <span>Voir les stages li&eacute;s</span>
        </a>
      <?php endif; ?>

      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Retour aux offres</strong>
        <span>Revenir au catalogue</span>
      </a>
    </div>
  </aside>
</section>

<?php if (!empty($relatedOffers)): ?>
  <section class="section" id="related-offers">
    <div class="section-header">
      <div>
        <h2 class="section-title">Autres offres de cette entreprise</h2>
        <p class="section-subtitle">Stages actuellement publi&eacute;s par <?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?>.</p>
      </div>
    </div>

    <div class="offers-grid">
      <?php foreach ($relatedOffers as $related): ?>
        <article class="offer-card">
          <header class="offer-card-header">
            <div class="offer-badge"><?= htmlspecialchars((string) $related['badge'], ENT_QUOTES) ?></div>
            <div>
              <h3 class="offer-title"><?= htmlspecialchars((string) $related['title'], ENT_QUOTES) ?></h3>
              <p class="offer-company"><?= htmlspecialchars((string) $related['company'], ENT_QUOTES) ?></p>
            </div>
          </header>
          <div class="offer-meta">
            <span><?= htmlspecialchars((string) $related['duration'], ENT_QUOTES) ?></span>
            <span><?= htmlspecialchars((string) $related['salary'], ENT_QUOTES) ?></span>
          </div>
          <div class="offer-skills">
            <?php foreach (($related['skills'] ?? []) as $skill): ?>
              <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
          </div>
          <footer class="offer-footer">
            <div class="offer-tagline"><?= htmlspecialchars((string) $related['tagline'], ENT_QUOTES) ?></div>
            <div class="offer-actions">
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $related['id']), ENT_QUOTES) ?>" class="btn btn-outline">D&eacute;tails</a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
