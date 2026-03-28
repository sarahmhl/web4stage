<?php
$stats = is_array($stats ?? null) ? $stats : [];
$attentionStats = is_array($attentionStats ?? null) ? $attentionStats : [];
$recentApplications = is_array($recentApplications ?? null) ? $recentApplications : [];
$studentsToFollowUp = is_array($studentsToFollowUp ?? null) ? $studentsToFollowUp : [];
$companiesToFollowUp = is_array($companiesToFollowUp ?? null) ? $companiesToFollowUp : [];
$latestFeedbacks = is_array($latestFeedbacks ?? null) ? $latestFeedbacks : [];
?>
<header class="dashboard-header">
  <div class="dashboard-title-block">
    <span class="dashboard-kicker">Tableau de bord pilote</span>
    <div class="dashboard-heading-row">
      <h1 class="dashboard-title">Bienvenue, <?= htmlspecialchars($pilotName, ENT_QUOTES) ?></h1>
    </div>
    <span class="pill-role pill-role--inline">Pilote</span>
    <p class="dashboard-subtitle">
      Suivez la promotion, les candidatures, les entreprises partenaires et les retours étudiants depuis un espace plus complet.
    </p>
  </div>
</header>

<section class="dashboard-lead" aria-label="Résumé pilote">
  <article class="dash-card dashboard-lead-card">
    <div class="dashboard-lead-head">
      <div class="dashboard-lead-copy">
        <span class="pill-small">Suivi promotion</span>
        <h2 class="dashboard-lead-title">Vue rapide de la promo</h2>
        <p class="dashboard-lead-text">Les indicateurs importants et les relances utiles sont regroupés ici.</p>
      </div>

      <div class="dashboard-lead-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les relances</a>
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Ouvrir les entreprises</a>
      </div>
    </div>

    <div class="dashboard-lead-stats">
      <div class="dashboard-lead-stat">
        <span>Étudiants suivis</span>
        <strong><?= (int) ($stats['students'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Dossiers actifs</span>
        <strong><?= (int) ($stats['applications'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Sans candidature</span>
        <strong><?= (int) ($attentionStats['studentsWithoutApplications'] ?? 0) ?></strong>
      </div>
      <div class="dashboard-lead-stat">
        <span>Entreprises à suivre</span>
        <strong><?= (int) ($attentionStats['companiesWithoutApplications'] ?? 0) ?></strong>
      </div>
    </div>
  </article>
</section>

<section class="dashboard-grid dashboard-grid--summary">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Promotion</span>
      <span class="pill-small"><?= (int) ($stats['students'] ?? 0) ?> étudiants</span>
    </header>
    <ul class="list-compact">
      <li><span>Étudiants suivis</span><strong><?= (int) ($stats['students'] ?? 0) ?></strong></li>
      <li><span>Sans candidature</span><strong><?= (int) ($attentionStats['studentsWithoutApplications'] ?? 0) ?></strong></li>
      <li><span>Dossiers en attente</span><strong><?= (int) ($attentionStats['studentsWithPending'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Vous voyez tout de suite si la promotion avance ou si certains profils restent trop en retrait.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Candidatures</span>
      <span class="pill-small"><?= (int) ($stats['applications'] ?? 0) ?> actives</span>
    </header>
    <ul class="list-compact">
      <li><span>Candidatures actives</span><strong><?= (int) ($stats['applications'] ?? 0) ?></strong></li>
      <li><span>Entretiens</span><strong><?= (int) ($stats['interviews'] ?? 0) ?></strong></li>
      <li><span>Étudiants en entretien</span><strong><?= (int) ($attentionStats['studentsWithInterviews'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Ce bloc aide à suivre le rythme réel de la promo, pas seulement le volume brut.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Entreprises</span>
      <span class="pill-small"><?= (int) ($stats['companies'] ?? 0) ?> partenaires</span>
    </header>
    <ul class="list-compact">
      <li><span>Entreprises suivies</span><strong><?= (int) ($stats['companies'] ?? 0) ?></strong></li>
      <li><span>Avec au moins une offre</span><strong><?= (int) ($attentionStats['companiesWithOffers'] ?? 0) ?></strong></li>
      <li><span>Sans candidature</span><strong><?= (int) ($attentionStats['companiesWithoutApplications'] ?? 0) ?></strong></li>
    </ul>
    <p class="dashboard-card-note">Vous pouvez vite voir si les partenaires sont actifs ou au contraire trop silencieux.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Retours terrain</span>
      <span class="pill-small">&#9733; <?= (int) ($stats['feedbacks'] ?? 0) ?> avis</span>
    </header>
    <ul class="list-compact">
      <li><span>Avis étudiants</span><strong><?= (int) ($stats['feedbacks'] ?? 0) ?></strong></li>
      <li><span>Moyenne récente</span><strong><?= ($attentionStats['averageFeedback'] ?? null) !== null ? htmlspecialchars((string) $attentionStats['averageFeedback'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
      <li><span>Accès direct</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('pilote/avis'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
    </ul>
    <p class="dashboard-card-note">Les signaux faibles remontent ici plus vite pour garder un vrai suivi pédagogique.</p>
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
      <span class="pill-small">&#128188; Offres</span>
      <h3 class="action-card-title action-card-title--icon"><span class="card-title-icon" aria-hidden="true">&#128188;</span><span>Gérer les offres de stage</span></h3>
      <p class="action-card-text">Créer, modifier ou supprimer les offres rattachées aux entreprises suivies.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/offres'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les offres</a>
    </article>

    <article class="action-card">
      <span class="pill-small">Étudiants</span>
      <h3 class="action-card-title">Gérer les comptes étudiants</h3>
      <p class="action-card-text">Créer, mettre à jour ou supprimer les comptes étudiants de la promotion.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les étudiants</a>
    </article>

    <article class="action-card">
      <span class="pill-small">&#128221; Suivi</span>
      <h3 class="action-card-title action-card-title--icon"><span class="card-title-icon" aria-hidden="true">&#128221;</span><span>Préparer les relances</span></h3>
      <p class="action-card-text">Repérez les profils peu actifs ou sans candidature récente.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="btn btn-outline">Préparer les relances</a>
    </article>

    <article class="action-card">
      <span class="pill-small">&#127970; Entreprises</span>
      <h3 class="action-card-title action-card-title--icon"><span class="card-title-icon" aria-hidden="true">&#127970;</span><span>Gérer les entreprises</span></h3>
      <p class="action-card-text">Maintenez les fiches partenaires et leurs coordonnées à jour.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" class="btn btn-outline">Gérer les entreprises</a>
    </article>

    <article class="action-card">
      <span class="pill-small">&#9733; Avis</span>
      <h3 class="action-card-title action-card-title--icon"><span class="card-title-icon" aria-hidden="true">&#9733;</span><span>Consulter les avis sur la formation</span></h3>
      <p class="action-card-text">Visualisez les retours laissés par les étudiants sans quitter l’espace pilote.</p>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/avis'), ENT_QUOTES) ?>" class="btn btn-outline">&#9733; Voir les retours</a>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Points d’attention</h2>
      <p class="section-subtitle">Les zones à traiter rapidement pour garder une promo bien suivie.</p>
    </div>
  </div>

  <div class="dashboard-grid dashboard-grid--three">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Relances étudiantes</span>
        <span class="pill-small">Priorité</span>
      </header>
      <ul class="list-compact">
        <li><span>Sans candidature</span><strong><?= (int) ($attentionStats['studentsWithoutApplications'] ?? 0) ?></strong></li>
        <li><span>Avec dossier en attente</span><strong><?= (int) ($attentionStats['studentsWithPending'] ?? 0) ?></strong></li>
        <li><span>En entretien</span><strong><?= (int) ($attentionStats['studentsWithInterviews'] ?? 0) ?></strong></li>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="link-soft">Ouvrir le suivi détaillé -&gt;</a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Couverture entreprises</span>
        <span class="pill-small">Partenaires</span>
      </header>
      <ul class="list-compact">
        <li><span>Avec offres</span><strong><?= (int) ($attentionStats['companiesWithOffers'] ?? 0) ?></strong></li>
        <li><span>Sans candidature</span><strong><?= (int) ($attentionStats['companiesWithoutApplications'] ?? 0) ?></strong></li>
        <li><span>Accès direct</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>">Ouvrir</a></strong></li>
      </ul>
      <p class="dashboard-card-note">Vous savez immédiatement quelles entreprises demandent un vrai suivi commercial ou relationnel.</p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Ambiance promo</span>
        <span class="pill-small">Feedback</span>
      </header>
      <ul class="list-compact">
        <li><span>Moyenne récente</span><strong><?= ($attentionStats['averageFeedback'] ?? null) !== null ? htmlspecialchars((string) $attentionStats['averageFeedback'], ENT_QUOTES) . '/5' : 'N/A' ?></strong></li>
        <li><span>Retours disponibles</span><strong><?= count($latestFeedbacks) ?></strong></li>
        <li><span>Canal ouvert</span><strong><a href="<?= htmlspecialchars(\Core\Url::route('pilote/avis'), ENT_QUOTES) ?>">Lire</a></strong></li>
      </ul>
      <p class="dashboard-card-note">Les ressentis étudiants sont visibles plus rapidement pour déclencher les bonnes actions d’accompagnement.</p>
    </article>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Relances prioritaires</h2>
      <p class="section-subtitle">Les profils qui demandent une lecture rapide côté pilote.</p>
    </div>
    <div class="section-actions">
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="link-soft">Voir tout le suivi -></a>
    </div>
  </div>

  <?php if ($studentsToFollowUp !== []): ?>
    <div class="table-shell">
      <table class="data-table">
        <thead>
          <tr>
            <th>Étudiant</th>
            <th>Candidatures</th>
            <th>En attente</th>
            <th>Entretiens</th>
            <th>&#9829; Wish-list</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($studentsToFollowUp as $student): ?>
            <tr>
              <td><?= htmlspecialchars(trim((string) $student['prenom'] . ' ' . (string) $student['nom']), ENT_QUOTES) ?></td>
              <td><?= (int) ($student['applications_count'] ?? 0) ?></td>
              <td><?= (int) ($student['pending_count'] ?? 0) ?></td>
              <td><?= (int) ($student['interviews_count'] ?? 0) ?></td>
              <td><?= (int) ($student['wishlist_count'] ?? 0) ?></td>
              <td>
                <div class="table-actions">
                  <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants?id=' . (int) $student['id_utilisateur']), ENT_QUOTES) ?>" class="btn btn-outline">Voir le compte</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <h3 class="empty-state-title">Aucune relance prioritaire</h3>
      <p class="empty-state-text">La promotion semble bien suivre pour le moment. Vous pouvez tout de même vérifier les comptes et les offres publiées.</p>
      <div class="empty-state-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants'), ENT_QUOTES) ?>" class="btn btn-outline">Voir les étudiants</a>
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/offres'), ENT_QUOTES) ?>" class="btn btn-primary">Voir les offres</a>
      </div>
    </div>
  <?php endif; ?>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Vue terrain</h2>
      <p class="section-subtitle">Les derniers signaux de la plateforme pour garder un tableau de bord vraiment chargé.</p>
    </div>
  </div>

  <div class="dashboard-grid dashboard-grid--three">
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Candidatures récentes</span>
        <span class="pill-small"><?= count($recentApplications) ?> elements</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($recentApplications as $application): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $application['etudiant_prenom'] . ' ' . (string) $application['etudiant_nom']), ENT_QUOTES) ?> - <?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></span>
            <strong><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="link-soft">Voir le flux complet -></a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">Entreprises à suivre</span>
        <span class="pill-small"><?= count($companiesToFollowUp) ?> structures</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($companiesToFollowUp as $company): ?>
          <li>
            <span><?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?></span>
            <strong><?= (int) ($company['applications_count'] ?? 0) ?> candidature(s)</strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/entreprises'), ENT_QUOTES) ?>" class="link-soft">Ouvrir les entreprises -></a>
      </p>
    </article>

    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title">&#9733; Derniers avis</span>
        <span class="pill-small"><?= count($latestFeedbacks) ?> retours</span>
      </header>
      <ul class="list-compact">
        <?php foreach ($latestFeedbacks as $feedback): ?>
          <li>
            <span><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
            <strong><?= (int) ($feedback['note'] ?? 0) ?>/5</strong>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="dashboard-card-note">
        <a href="<?= htmlspecialchars(\Core\Url::route('pilote/avis'), ENT_QUOTES) ?>" class="link-soft">&#9733; Voir tous les avis -></a>
      </p>
    </article>
  </div>
</section>


