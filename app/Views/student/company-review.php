<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Évaluation entreprise</span>
    <h1 class="page-heading-title">Évaluer une entreprise</h1>
    <p class="page-heading-subtitle">
      Choisissez une entreprise et publiez votre retour sur le processus ou l expérience de stage.
    </p>
  </div>
</header>

<section class="page-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Entreprises</h2>
    <div class="management-offer-list">
      <?php foreach (($companies ?? []) as $company): ?>
        <a href="<?= htmlspecialchars(\Core\Url::route('etudiant/entreprises/evaluer?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="management-offer-link<?= (int) ($selectedCompanyId ?? 0) === (int) $company['id_entreprise'] ? ' management-offer-link--active' : '' ?>">
          <strong><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Nouvelle évaluation</span>
      <span class="pill-small">Entreprise</span>
    </header>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('etudiant/entreprises/evaluer'), ENT_QUOTES) ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
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
        <div class="form-group form-group--full">
          <label for="comment">Commentaire</label>
          <textarea id="comment" name="comment" class="form-control form-control--textarea" required></textarea>
        </div>
      </div>
      <div class="form-footer offer-form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer l évaluation</button>
      </div>
    </form>
  </article>
</section>

<?php if (!empty($selectedCompany)): ?>
  <section class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Fiche sélectionnée</h2>
        <p class="section-subtitle"><?= htmlspecialchars((string) $selectedCompany['nom'], ENT_QUOTES) ?> · <?= htmlspecialchars((string) ($selectedCompany['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></p>
      </div>
    </div>

    <div class="dashboard-grid">
      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title">Coordonnées</span>
          <span class="pill-small"><?= $selectedCompany['average_rating'] !== null ? htmlspecialchars((string) $selectedCompany['average_rating'], ENT_QUOTES) . '/5' : 'Aucune note' ?></span>
        </header>
        <ul class="list-compact">
          <li>
            <span>Email</span>
            <strong><?= htmlspecialchars((string) ($selectedCompany['email_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Téléphone</span>
            <strong><?= htmlspecialchars((string) ($selectedCompany['telephone_contact'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Offres publiées</span>
            <strong><?= (int) ($selectedCompany['offers_count'] ?? 0) ?></strong>
          </li>
        </ul>
      </article>

      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title">Avis publiés</span>
          <span class="pill-small"><?= (int) ($selectedCompany['reviews_count'] ?? 0) ?> avis</span>
        </header>
        <?php if (!empty($selectedCompany['reviews'])): ?>
          <ul class="list-compact">
            <?php foreach (array_slice($selectedCompany['reviews'], 0, 3) as $review): ?>
              <li>
                <span><?= htmlspecialchars(trim((string) $review['prenom'] . ' ' . (string) $review['nom']), ENT_QUOTES) ?></span>
                <strong><?= (int) ($review['note'] ?? 0) ?>/5</strong>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="action-card-text">Aucun avis enregistré pour cette entreprise.</p>
        <?php endif; ?>
      </article>
    </div>
  </section>
<?php endif; ?>
