<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord pilote</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($pilotName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Pilote</span>
    <p class="dashboard-subtitle">
      Suivez la promotion, l activité des candidatures, les retours étudiants et les entreprises à relancer.
    </p>
  </div>
</header>

<section class="dashboard-grid">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Vue promotion</span>
      <span class="pill-small">Suivi pédagogique</span>
    </header>
    <ul class="list-compact">
      <li><span>Étudiants suivis</span><strong><?= (int) $stats['students'] ?></strong></li>
      <li><span>Candidatures actives</span><strong><?= (int) $stats['applications'] ?></strong></li>
      <li><span>Entretiens</span><strong><?= (int) $stats['interviews'] ?></strong></li>
    </ul>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Partenaires</span>
      <span class="pill-small">Entreprises</span>
    </header>
    <ul class="list-compact">
      <li><span>Entreprises suivies</span><strong><?= (int) $stats['companies'] ?></strong></li>
      <li><span>Avis étudiants</span><strong><?= (int) $stats['feedbacks'] ?></strong></li>
      <li><span>Accès rapide</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Actions rapides</h2>
      <p class="section-subtitle">Les raccourcis les plus utiles pour accompagner la promotion.</p>
    </div>
  </div>

  <div class="action-grid">
    <article class="action-card">
      <span class="pill-small">Offres</span>
      <h3 class="action-card-title">Ajouter une offre de stage</h3>
      <p class="action-card-text">Créez une nouvelle offre avec son entreprise, ses compétences, sa durée et son visuel.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/offres/ajouter'), ENT_QUOTES) ?>" class="btn btn-outline">Ajouter une offre</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Avis</span>
      <h3 class="action-card-title">Consulter les avis sur la formation</h3>
      <p class="action-card-text">Visualisez les retours laissés par les étudiants.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/avis'), ENT_QUOTES) ?>" class="btn btn-outline">Voir les retours</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Suivi</span>
      <h3 class="action-card-title">Relancer les étudiants</h3>
      <p class="action-card-text">Repérez les profils peu actifs ou sans candidature récente.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="btn btn-outline">Préparer les relances</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Entreprises</span>
      <h3 class="action-card-title">Suivre les entreprises</h3>
      <p class="action-card-text">Consultez les partenaires, leurs offres et les retours disponibles.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Ouvrir le suivi</a>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Suivi des candidatures</h2>
      <p class="section-subtitle">Derniers dossiers à surveiller au niveau de la promotion.</p>
    </div>
  </div>

  <div class="dashboard-grid">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Candidatures récentes</span>
        <span class="pill-small"><?= count($recentApplications ?? []) ?> éléments</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($recentApplications ?? []) as $application): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $application['etudiant_prenom'] . ' ' . (string) $application['etudiant_nom']), ENT_QUOTES) ?> · <?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Étudiants à relancer</span>
        <span class="pill-small"><?= count($studentsToFollowUp ?? []) ?> profils</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($studentsToFollowUp ?? []) as $student): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $student['prenom'] . ' ' . (string) $student['nom']), ENT_QUOTES) ?></span>
            <strong><?= (int) ($student['applications_count'] ?? 0) ?> candidature(s)</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Entreprises à suivre</span>
        <span class="pill-small"><?= count($companiesToFollowUp ?? []) ?> structures</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($companiesToFollowUp ?? []) as $company): ?>
          <li>
            <span><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
            <strong><?= (int) ($company['applications_count'] ?? 0) ?> candidature(s)</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Derniers avis</span>
        <span class="pill-small"><?= count($latestFeedbacks ?? []) ?> retours</span>
      </header>
      <ul class="list-compact">
        <?php foreach (($latestFeedbacks ?? []) as $feedback): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
            <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </article>
  </div>
</section>
