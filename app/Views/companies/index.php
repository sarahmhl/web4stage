<?php // Vue de la liste des entreprises referencees avec resume et acces a leur fiche. ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Entreprises</span>
    <h1 class="page-heading-title">Entreprises référencées</h1>
    <p class="page-heading-subtitle">
      Consultez les structures partenaires, leurs coordonnées et les offres de stage liées.
    </p>
  </div>
</header>

<section class="dashboard-grid">
  <?php foreach (($companies ?? []) as $company): ?>
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title"><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
        <span class="pill-small"><?= htmlspecialchars((string) ($company['ville'] ?: 'Ville non précisée'), ENT_QUOTES) ?></span>
      </header>
      <p class="action-card-text"><?= htmlspecialchars((string) ($company['description'] ?: 'Aucune description disponible pour le moment.'), ENT_QUOTES) ?></p>
      <ul class="list-compact">
        <li>
          <span>Secteur</span>
          <strong><?= htmlspecialchars((string) ($company['secteur'] ?: 'Non renseigné'), ENT_QUOTES) ?></strong>
        </li>
        <li>
          <span>Offres publiées</span>
          <strong><?= (int) ($company['offers_count'] ?? 0) ?></strong>
        </li>
        <li>
          <span>Note moyenne</span>
          <strong><?= $company['average_rating'] !== null ? htmlspecialchars((string) $company['average_rating'], ENT_QUOTES) . ' / 5' : 'Aucune note' ?></strong>
        </li>
      </ul>
      <a href="<?= htmlspecialchars(\Core\Url::route('entreprises/detail?id=' . (int) $company['id_entreprise']), ENT_QUOTES) ?>" class="btn btn-outline">Voir la fiche</a>
    </article>
  <?php endforeach; ?>
</section>
