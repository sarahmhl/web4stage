<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Retours étudiants</span>
    <h1 class="page-heading-title">Avis publiés par les étudiants</h1>
    <p class="page-heading-subtitle">
      Consultez les retours sur l’accompagnement, la plateforme et la préparation au stage.
    </p>
  </div>
</header>

<section class="dashboard-grid">
  <?php foreach (($feedbacks ?? []) as $feedback): ?>
    <article class="dash-card">
      <header class="dash-card-header">
        <span class="dash-card-title"><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
        <span class="pill-small"><?= (int) ($feedback['note'] ?? 0) ?>/5</span>
      </header>
      <p class="action-card-text"><?= nl2br(htmlspecialchars((string) $feedback['commentaire'], ENT_QUOTES)) ?></p>
    </article>
  <?php endforeach; ?>
</section>
