<?php
$selectedOffer = is_array($selectedOffer ?? null) ? $selectedOffer : null;
$selectedOfferId = (int) ($selectedOfferId ?? 0);
$skillsValue = '';
if ($selectedOffer !== null) {
    if (is_array($selectedOffer['skills'] ?? null)) {
        $skillsValue = implode(', ', $selectedOffer['skills']);
    } elseif (is_scalar($selectedOffer['skills'] ?? null)) {
        $skillsValue = (string) $selectedOffer['skills'];
    }
}
?>
<header class="dashboard-header">
  <div class="dashboard-title-block">
    <h1 class="dashboard-title">Modifier les offres de stage</h1>
    <p class="dashboard-subtitle">
      Selectionnez une offre existante, ajustez son contenu puis enregistrez les modifications.
    </p>
  </div>
  <span class="pill-role">Role : Admin</span>
</header>

<?php if (!empty($success)): ?>
  <p class="auth-hint auth-hint--success"><?= htmlspecialchars($success, ENT_QUOTES) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <p class="auth-hint auth-hint--error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
<?php endif; ?>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Offres a modifier</h2>
    <p class="side-card-text">
      Choisissez une offre dans la liste pour precharger le formulaire d edition.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Gerer les entreprises</strong>
        <span>Mettre a jour les partenaires relies aux offres</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach ($offers as $offer): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier?id=' . (int) $offer['id_offre']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $offer['id_offre'] === $selectedOfferId ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars((string) $offer['titre'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) $offer['entreprise_nom'], ENT_QUOTES) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Edition de l offre</span>
      <span class="pill-small">Mise a jour admin</span>
    </header>

    <?php if ($selectedOffer === null): ?>
      <p class="auth-hint">Aucune offre n est disponible pour la modification.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/offres/modifier'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
        <input type="hidden" name="id_offre" value="<?= (int) $selectedOffer['id_offre'] ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="id_entreprise">Entreprise</label>
            <select id="id_entreprise" name="id_entreprise" class="form-control" required>
              <option value="">Selectionner une entreprise</option>
              <?php foreach ($companies as $company): ?>
                <option
                  value="<?= (int) $company['id'] ?>"
                  <?= (int) $selectedOffer['id_entreprise'] === (int) $company['id'] ? 'selected' : '' ?>
                >
                  <?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="titre">Titre du stage</label>
            <input
              type="text"
              id="titre"
              name="titre"
              class="form-control"
              value="<?= htmlspecialchars((string) $selectedOffer['titre'], ENT_QUOTES) ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="date_offre">Date de publication</label>
            <input
              type="date"
              id="date_offre"
              name="date_offre"
              class="form-control"
              value="<?= htmlspecialchars((string) $selectedOffer['date_offre'], ENT_QUOTES) ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="duree_mois">Duree en mois</label>
            <input
              type="number"
              id="duree_mois"
              name="duree_mois"
              class="form-control"
              min="1"
              value="<?= htmlspecialchars((string) $selectedOffer['duree_mois'], ENT_QUOTES) ?>"
            />
          </div>

          <div class="form-group">
            <label for="base_remuneration">Base de remuneration</label>
            <input
              type="number"
              step="0.01"
              id="base_remuneration"
              name="base_remuneration"
              class="form-control"
              value="<?= htmlspecialchars((string) $selectedOffer['base_remuneration'], ENT_QUOTES) ?>"
            />
          </div>

          <div class="form-group">
            <label for="image_path">Illustration</label>
            <select id="image_path" name="image_path" class="form-control">
              <option value="">Selectionner une image</option>
              <?php foreach ($imageOptions as $option): ?>
                <option
                  value="<?= htmlspecialchars($option['file'], ENT_QUOTES) ?>"
                  <?= (string) $selectedOffer['image_path'] === $option['file'] ? 'selected' : '' ?>
                >
                  <?= htmlspecialchars($option['label'], ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group form-group--full">
            <label for="skills">Competences</label>
            <input
              type="text"
              id="skills"
              name="skills"
              class="form-control"
              value="<?= htmlspecialchars($skillsValue, ENT_QUOTES) ?>"
              placeholder="Ex : SQL, Power BI, Reporting"
            />
          </div>

          <div class="form-group form-group--full">
            <label for="description">Description</label>
            <textarea
              id="description"
              name="description"
              class="form-control form-control--textarea"
              required
            ><?= htmlspecialchars((string) $selectedOffer['description'], ENT_QUOTES) ?></textarea>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-admin'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          </div>
          <button
            type="submit"
            formaction="<?= htmlspecialchars(\Core\Url::route('admin/offres/supprimer'), ENT_QUOTES) ?>"
            formmethod="post"
            class="btn btn-outline btn-outline--danger"
            onclick="return confirm('Supprimer cette offre ?');"
          >
            Supprimer l offre
          </button>
        </div>
      </form>
    <?php endif; ?>
  </article>
</section>
