<?php
  // Vue detail d une offre avec actions rapides, entreprise associee et offres liees.
  $offerImage = \Core\Url::asset('assets/img/offers/' . ((string) ($offer['image'] ?? 'devweb.jpeg')));
  $defaultImage = \Core\Url::asset('assets/img/web4stage.png');
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Detail de l'offre</span>
    <h1 class="page-heading-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      Consultez la mission, l'entreprise, les competences attendues et postulez directement depuis cette fiche.
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
      <div class="detail-header-row">
        <div>
          <span class="pill-small"><?= htmlspecialchars((string) $offer['badge'], ENT_QUOTES) ?></span>
          <p class="detail-company">
            <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $offer['company_id']), ENT_QUOTES) ?>">
              <?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?>
            </a>
            <?php if (!empty($offer['city'])): ?>
              · <?= htmlspecialchars((string) $offer['city'], ENT_QUOTES) ?>
            <?php endif; ?>
          </p>
        </div>
        <div class="detail-actions">
          <?php if (\Core\Auth::checkRole(\Core\Auth::ROLE_ETUDIANT)): ?>
            <form method="post" action="<?= htmlspecialchars(\Core\Url::route('wishlist/toggle'), ENT_QUOTES) ?>">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
              <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
              <input type="hidden" name="redirect_to" value="offres/detail?id=<?= (int) $offer['id'] ?>" />
              <button type="submit" class="btn <?= !empty($isWishlisted) ? 'btn-primary' : 'btn-outline' ?>">
                <?= !empty($isWishlisted) ? 'Retirer de la wish-list' : 'Ajouter a la wish-list' ?>
              </button>
            </form>
          <?php endif; ?>

          <?php if (!empty($canApply)): ?>
            <a href="<?= htmlspecialchars(\Core\Url::route('candidatures/nouvelle?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-primary">
              Postuler
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="detail-stats">
        <div class="stat-pill">
          <span>Duree</span>
          <strong><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill stat-pill--salary">
          <span>Remuneration</span>
          <strong><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Candidatures</span>
          <strong><?= (int) ($offer['applications_count'] ?? 0) ?></strong>
        </div>
        <div class="stat-pill">
          <span>Wish-list</span>
          <strong><?= (int) ($offer['wishlist_count'] ?? 0) ?></strong>
        </div>
      </div>

      <section class="detail-section">
        <h2>Description du stage</h2>
        <p><?= nl2br(htmlspecialchars((string) ($offer['description'] ?? ''), ENT_QUOTES)) ?></p>
      </section>

      <section class="detail-section">
        <h2>Competences attendues</h2>
        <div class="offer-skills">
          <?php foreach (($offer['skills'] ?? []) as $skill): ?>
            <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="detail-section">
        <h2>Entreprise</h2>
        <div class="detail-meta-grid">
          <div>
            <span class="detail-label">Secteur</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($offer['company_sector'] ?? 'Non renseigne'), ENT_QUOTES) ?></strong>
          </div>
          <div>
            <span class="detail-label">Contact</span>
            <?php if (!empty($offer['company_email'])): ?>
              <a class="detail-link" href="mailto:<?= htmlspecialchars((string) $offer['company_email'], ENT_QUOTES) ?>">
                <strong class="detail-value"><?= htmlspecialchars((string) $offer['company_email'], ENT_QUOTES) ?></strong>
              </a>
            <?php else: ?>
              <strong class="detail-value">Non renseigne</strong>
            <?php endif; ?>
          </div>
          <div>
            <span class="detail-label">Telephone</span>
            <strong class="detail-value"><?= htmlspecialchars((string) ($offer['company_phone'] ?? 'Non renseigne'), ENT_QUOTES) ?></strong>
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
              <strong class="detail-value">Non renseigne</strong>
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

  <aside class="side-card">
    <h2 class="side-card-title">Acces rapides</h2>
    <p class="side-card-text">
      Retrouvez cette entreprise, ses autres offres et votre suivi de candidature.
    </p>
    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $offer['company_id']), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Voir la fiche entreprise</strong>
        <span>Contact, avis et offres liees</span>
      </a>
      <?php if (!empty($canApply)): ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('candidatures/nouvelle?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="management-offer-link">
          <strong>Postuler a cette offre</strong>
          <span>CV + lettre de motivation</span>
        </a>
      <?php endif; ?>
      <a href="<?= htmlspecialchars(\Core\Url::route('offres'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Revenir a la liste</strong>
        <span>Continuer a filtrer les offres</span>
      </a>
    </div>
  </aside>
</section>

<?php if (!empty($relatedOffers)): ?>
  <section class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Autres offres de cette entreprise</h2>
        <p class="section-subtitle">Stages actuellement publies par <?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?>.</p>
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
              <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $related['id']), ENT_QUOTES) ?>" class="btn btn-outline">Details</a>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
