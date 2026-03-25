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
      Maintenez a jour les fiches partenaires et leurs informations de contact.
    </p>
  </div>
</header>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Entreprises referencees</h2>
    <p class="side-card-text">
      Selectionnez une entreprise pour modifier sa fiche ou ajoutez un nouveau partenaire.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewCompany ? ' management-offer-link--active' : '' ?>">
        <strong>Nouvelle entreprise</strong>
        <span>Ajouter une nouvelle structure partenaire</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach (($companies ?? []) as $company): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('admin/entreprises?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $company['id_entreprise'] === $selectedCompanyId && !$isNewCompany ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non precisee'), ENT_QUOTES) ?> - <?= (int) ($company['offers_count'] ?? 0) ?> offre(s)</span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title"><?= $isNewCompany ? 'Nouvelle entreprise' : 'Edition de l entreprise' ?></span>
      <span class="pill-small"><?= $isNewCompany ? 'Creation' : 'Mise a jour' ?></span>
    </header>

    <?php if ($selectedCompany === null): ?>
      <p class="auth-hint">Aucune entreprise n est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/entreprises'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_entreprise" value="<?= (int) ($selectedCompany['id_entreprise'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="nom">Nom de l entreprise</label>
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
            <label for="telephone_contact">Telephone</label>
            <input type="text" id="telephone_contact" name="telephone_contact" class="form-control" value="<?= htmlspecialchars((string) ($selectedCompany['telephone_contact'] ?? ''), ENT_QUOTES) ?>" />
          </div>

          <div class="form-group form-group--full">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control form-control--textarea"><?= htmlspecialchars((string) ($selectedCompany['description'] ?? ''), ENT_QUOTES) ?></textarea>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-admin'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewCompany ? 'Creer l entreprise' : 'Enregistrer les modifications' ?></button>
          </div>
        </div>
      </form>

      <?php if (!$isNewCompany && (int) ($selectedCompany['id_entreprise'] ?? 0) > 0): ?>
        <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/entreprises/supprimer'), ENT_QUOTES) ?>" class="inline-action-form" onsubmit="return confirm('Supprimer cette entreprise ?');">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
          <input type="hidden" name="id_entreprise" value="<?= (int) ($selectedCompany['id_entreprise'] ?? 0) ?>" />
          <button type="submit" class="btn btn-outline btn-outline--danger">Supprimer l entreprise</button>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </article>
</section>
