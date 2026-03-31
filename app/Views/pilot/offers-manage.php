<?php
$selectedOffer = is_array($selectedOffer ?? null) ? $selectedOffer : null;
$selectedOfferId = (int) ($selectedOfferId ?? 0);
$isNewOffer = (bool) ($isNewOffer ?? false);
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
    <h1 class="dashboard-title">Gérer les offres de stage</h1>
    <p class="dashboard-subtitle">
      Créez, modifiez ou supprimez les offres de stage suivies par votre promotion.
    </p>
  </div>
  <span class="pill-role">Rôle : Pilote</span>
</header>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Offres disponibles</h2>
    <p class="side-card-text">
      Sélectionnez une offre pour la modifier ou créez une nouvelle publication.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/offres?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewOffer ? ' management-offer-link--active' : '' ?>">
        <strong>Nouvelle offre</strong>
        <span>Publier une nouvelle offre pour une entreprise</span>
      </a>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Gérer les entreprises</strong>
        <span>Mettre à jour les partenaires liés aux offres</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach (($offers ?? []) as $offer): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('pilote/offres?id=' . (int) $offer['id_offre']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $offer['id_offre'] === $selectedOfferId && !$isNewOffer ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars((string) $offer['titre'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) $offer['entreprise_nom'], ENT_QUOTES) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title"><?= $isNewOffer ? 'Nouvelle offre' : 'Édition de l\'offre' ?></span>
      <span class="pill-small"><?= $isNewOffer ? 'Création' : 'Mise à jour' ?></span>
    </header>

    <?php if ($selectedOffer === null): ?>
      <p class="auth-hint">Aucune offre n'est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('pilote/offres'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_offre" value="<?= (int) ($selectedOffer['id_offre'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="id_entreprise">Entreprise</label>
            <select id="id_entreprise" name="id_entreprise" class="form-control" required>
              <option value="">Sélectionner une entreprise</option>
              <?php foreach (($companies ?? []) as $company): ?>
                <option
                  value="<?= (int) $company['id'] ?>"
                  <?= (int) ($selectedOffer['id_entreprise'] ?? 0) === (int) $company['id'] ? 'selected' : '' ?>
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
              value="<?= htmlspecialchars((string) ($selectedOffer['titre'] ?? ''), ENT_QUOTES) ?>"
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
              value="<?= htmlspecialchars((string) ($selectedOffer['date_offre'] ?? ''), ENT_QUOTES) ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="duree_mois">Durée en mois</label>
            <input
              type="number"
              id="duree_mois"
              name="duree_mois"
              class="form-control"
              min="1"
              value="<?= htmlspecialchars((string) ($selectedOffer['duree_mois'] ?? ''), ENT_QUOTES) ?>"
            />
          </div>

          <div class="form-group">
            <label for="base_remuneration">Base de rémunération</label>
            <input
              type="number"
              step="0.01"
              id="base_remuneration"
              name="base_remuneration"
              class="form-control"
              value="<?= htmlspecialchars((string) ($selectedOffer['base_remuneration'] ?? ''), ENT_QUOTES) ?>"
            />
          </div>

          <div class="form-group">
            <label for="image_path">Illustration</label>
            <select id="image_path" name="image_path" class="form-control">
              <option value="">Sélectionner une image</option>
              <?php foreach (($imageOptions ?? []) as $option): ?>
                <option
                  value="<?= htmlspecialchars((string) $option['file'], ENT_QUOTES) ?>"
                  <?= (string) ($selectedOffer['image_path'] ?? '') === (string) $option['file'] ? 'selected' : '' ?>
                >
                  <?= htmlspecialchars((string) $option['label'], ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group form-group--full">
            <label for="skills">Compétences</label>
            <input
              type="text"
              id="skills"
              name="skills"
              class="form-control"
              value="<?= htmlspecialchars($skillsValue, ENT_QUOTES) ?>"
              placeholder="Ex : PHP, MVC, MySQL"
            />
            <p class="auth-hint offer-form-hint">Séparez les compétences avec des virgules.</p>
          </div>

          <div class="form-group form-group--full">
            <label for="description">Description</label>
            <textarea
              id="description"
              name="description"
              class="form-control form-control--textarea"
              required
            ><?= htmlspecialchars((string) ($selectedOffer['description'] ?? ''), ENT_QUOTES) ?></textarea>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-pilote'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewOffer ? 'Créer l\'offre' : 'Enregistrer les modifications' ?></button>
          </div>
          <?php if (!$isNewOffer && (int) ($selectedOffer['id_offre'] ?? 0) > 0): ?>
            <button
              type="submit"
              formaction="<?= htmlspecialchars(\Core\Url::route('pilote/offres/supprimer'), ENT_QUOTES) ?>"
              formmethod="post"
              class="btn btn-outline btn-outline--danger"
              onclick="return confirm('Supprimer cette offre ?');"
            >
              Supprimer l'offre
            </button>
          <?php endif; ?>
        </div>
      </form>
    <?php endif; ?>
  </article>
</section>
