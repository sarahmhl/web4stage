<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Évaluation entreprise</span>
    <h1 class="page-heading-title">Évaluer une entreprise</h1>
    <p class="page-heading-subtitle">
      Donnez votre ressenti sur le processus, l’expérience de stage ou la qualité des échanges dans une interface plus soignée.
    </p>
  </div>
</header>

<section class="page-layout journey-layout journey-layout--sidebar">
  <aside class="journey-side-stack">
    <article class="journey-side-card">
      <span class="journey-card-kicker">Entreprises</span>
      <h2 class="journey-side-title">Choisir une structure</h2>
      <div class="management-offer-list">
        <?php foreach (($companies ?? []) as $company): ?>
          <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="management-offer-link<?= (int) ($selectedCompanyId ?? 0) === (int) $company['id_entreprise'] ? ' management-offer-link--active' : '' ?>">
            <strong><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></strong>
            <span><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </article>

    <?php if (!empty($selectedCompany)): ?>
      <article class="journey-side-card journey-side-card--accent">
        <span class="journey-card-kicker">Entreprise sélectionnée</span>
        <h2 class="journey-side-title"><?= htmlspecialchars((string) $selectedCompany['nom'], ENT_QUOTES) ?></h2>
        <ul class="journey-summary-list">
          <li><span>Ville</span><strong><?= htmlspecialchars((string) ($selectedCompany['ville'] ?: 'Non précisée'), ENT_QUOTES) ?></strong></li>
          <li><span>Note</span><strong><?= $selectedCompany['average_rating'] !== null ? htmlspecialchars((string) $selectedCompany['average_rating'], ENT_QUOTES) . '/5' : 'Aucune note' ?></strong></li>
          <li><span>Offres</span><strong><?= (int) ($selectedCompany['offers_count'] ?? 0) ?></strong></li>
        </ul>
      </article>
    <?php endif; ?>
  </aside>

  <article class="journey-main-card">
    <div class="journey-card-head">
      <div>
        <span class="journey-card-kicker">Évaluation</span>
        <h2 class="journey-card-title">Publier un retour entreprise</h2>
        <p class="journey-card-text">
          Mettez en avant la qualité des échanges, le sérieux du suivi ou l’expérience vécue pendant le stage.
        </p>
      </div>
    </div>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('entreprises/evaluer'), ENT_QUOTES) ?>" data-js-validate>
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />

      <div class="journey-form-stack">
        <section class="journey-form-block">
          <div class="journey-form-head">
            <h3>Entreprise et note</h3>
            <p>Sélectionnez la structure puis attribuez une note claire.</p>
          </div>

          <div class="offer-form-grid">
            <div class="form-group">
              <label for="company_id">Entreprise</label>
              <select id="company_id" name="company_id" class="form-control" required>
                <option value="">Choisir une entreprise</option>
                <?php foreach (($companies ?? []) as $company): ?>
                  <option value="<?= (int) $company['id_entreprise'] ?>" <?= (int) ($selectedCompanyId ?? 0) === (int) $company['id_entreprise'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="rating">Note</label>
              <select id="rating" name="rating" class="form-control" required>
                <option value="">Choisir</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                  <option value="<?= $i ?>"><?= $i ?>/5</option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
        </section>

        <section class="journey-form-block">
          <div class="journey-form-head">
            <h3>Commentaire</h3>
            <p>Partagez un retour utile pour les prochains candidats.</p>
          </div>

          <div class="form-group form-group--full">
            <label for="comment">Mon avis</label>
            <textarea id="comment" name="comment" class="form-control form-control--textarea" required></textarea>
          </div>
        </section>
      </div>

      <div class="form-footer offer-form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer l’évaluation</button>
      </div>
    </form>
  </article>
</section>

<?php if (!empty($selectedCompany)): ?>
  <section class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Fiche sélectionnée</h2>
        <p class="section-subtitle"><?= htmlspecialchars((string) $selectedCompany['nom'], ENT_QUOTES) ?> - <?= htmlspecialchars((string) ($selectedCompany['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></p>
      </div>
    </div>

    <div class="journey-review-grid">
      <article class="journey-review-card">
        <header class="journey-card-head journey-card-head--compact">
          <div>
            <span class="journey-card-kicker">Coordonnées</span>
            <h3 class="journey-list-title">Informations utiles</h3>
          </div>
          <span class="pill-small"><?= $selectedCompany['average_rating'] !== null ? htmlspecialchars((string) $selectedCompany['average_rating'], ENT_QUOTES) . '/5' : 'Aucune note' ?></span>
        </header>
        <ul class="journey-summary-list">
          <li><span>Email</span><strong><?= htmlspecialchars((string) ($selectedCompany['email_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong></li>
          <li><span>Téléphone</span><strong><?= htmlspecialchars((string) ($selectedCompany['telephone_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong></li>
          <li><span>Offres publiées</span><strong><?= (int) ($selectedCompany['offers_count'] ?? 0) ?></strong></li>
        </ul>
      </article>

      <article class="journey-review-card">
        <header class="journey-card-head journey-card-head--compact">
          <div>
            <span class="journey-card-kicker">Avis publiés</span>
            <h3 class="journey-list-title">Retours récents</h3>
          </div>
          <span class="pill-small"><?= (int) ($selectedCompany['reviews_count'] ?? 0) ?> avis</span>
        </header>

        <?php if (!empty($selectedCompany['reviews'])): ?>
          <ul class="journey-review-list">
            <?php foreach (array_slice($selectedCompany['reviews'], 0, 3) as $review): ?>
              <li>
                <span><?= htmlspecialchars(trim((string) $review['prenom'] . ' ' . (string) $review['nom']), ENT_QUOTES) ?></span>
                <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="journey-review-text">Aucun avis enregistré pour cette entreprise.</p>
        <?php endif; ?>
      </article>
    </div>
  </section>
<?php endif; ?>
