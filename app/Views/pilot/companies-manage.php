<?php
$selectedCompany = is_array($selectedCompany ?? null) ? $selectedCompany : null;
$selectedCompanyId = (int) ($selectedCompanyId ?? 0);
$isNewCompany = (bool) ($isNewCompany ?? false);
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Entreprises</span>
    <h1 class="page-heading-title">Gestion des entreprises</h1>
    <p class="page-heading-subtitle">
      Créez, mettez à jour ou supprimez les fiches partenaires utiles à la promotion.
    </p>
  </div>
</header>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Entreprises référencées</h2>
    <p class="side-card-text">
      Sélectionnez une entreprise pour modifier sa fiche ou ajoutez un nouveau partenaire.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewCompany ? ' management-offer-link--active' : '' ?>">
        <strong>Nouvelle entreprise</strong>
        <span>Ajouter une nouvelle structure partenaire</span>
      </a>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/offres'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Gérer les offres</strong>
        <span>Mettre à jour les publications associées</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach (($companies ?? []) as $company): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $company['id_entreprise'] === $selectedCompanyId && !$isNewCompany ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?> - <?= (int) ($company['offers_count'] ?? 0) ?> offre(s)</span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title"><?= $isNewCompany ? 'Nouvelle entreprise' : 'Édition de l’entreprise' ?></span>
      <span class="pill-small"><?= $isNewCompany ? 'Création' : 'Mise à jour' ?></span>
    </header>

    <?php if ($selectedCompany === null): ?>
      <p class="auth-hint">Aucune entreprise n’est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_entreprise" value="<?= (int) ($selectedCompany['id_entreprise'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="nom">Nom de l’entreprise</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['nom'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group">
            <label for="ville">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['ville'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group">
            <label for="secteur">Secteur</label>
            <input type="text" id="secteur" name="secteur" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['secteur'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group">
            <label for="site_web">Site web</label>
            <input type="url" id="site_web" name="site_web" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['site_web'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group">
            <label for="email_contact">E-mail de contact</label>
            <input type="email" id="email_contact" name="email_contact" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['email_contact'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group">
            <label for="telephone_contact">Téléphone</label>
            <input type="text" id="telephone_contact" name="telephone_contact" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['telephone_contact'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group form-group--full">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control form-control--textarea"><?= htmlspecialchars((string) ($selectedCompany['description'] ?? ''), ENT_QUOTES) ?></textarea>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-pilote'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewCompany ? 'Créer l’entreprise' : 'Enregistrer les modifications' ?></button>
          </div>
          <?php if (!$isNewCompany && (int) ($selectedCompany['id_entreprise'] ?? 0) > 0): ?>
            <button
              type="submit"
              formaction="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises/supprimer'), ENT_QUOTES) ?>"
              formmethod="post"
              class="btn btn-outline btn-outline--danger"
              onclick="return confirm('Supprimer cette entreprise ?');"
            >
              Supprimer l’entreprise
            </button>
          <?php endif; ?>
        </div>
      </form>
    <?php endif; ?>
  </article>
</section>

<?php
$paginationCurrentPage = (int) ($currentPage ?? 1);
$paginationTotalPages = (int) ($totalPages ?? 1);
$paginationPageParam = 'page';
$paginationLabel = 'Pagination des entreprises';
require __DIR__ . '/../partials/pagination.php';
?>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Vue d’ensemble</h2>
      <p class="section-subtitle">Résumé des entreprises, offres et avis disponibles.</p>
    </div>
  </div>

  <div class="table-shell">
    <table class="data-table">
      <thead>
        <tr>
          <th>Entreprise</th>
          <th>Ville</th>
          <th>Secteur</th>
          <th>Offres</th>
          <th>Note</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($companies ?? []) as $company): ?>
          <tr>
            <td>
              <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>">
                <?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?>
              </a>
            </td>
            <td><?= htmlspecialchars((string) ($company['ville'] ?: 'Non précisée'), ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigné'), ENT_QUOTES) ?></td>
            <td><?= (int) ($company['offers_count'] ?? 0) ?></td>
            <td><?= $company['average_rating'] !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . '/5' : 'Aucune note' ?></td>
            <td>
              <div class="table-actions">
                <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Modifier</a>
                <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Voir la fiche</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
