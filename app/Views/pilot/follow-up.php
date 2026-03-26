<?php ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Relances</span>
    <h1 class="page-heading-title">Étudiants à relancer</h1>
    <p class="page-heading-subtitle">
      Repérez rapidement les profils les moins actifs et les candidatures encore en attente.
    </p>
  </div>
</header>

<section class="page-layout">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Étudiants</span>
      <span class="pill-small"><?= count($students ?? []) ?> profils</span>
    </header>
    <div class="table-shell">
      <table class="data-table">
        <thead>
          <tr>
            <th>Étudiant</th>
            <th>Email</th>
            <th>Candidatures</th>
            <th>En attente</th>
            <th>Wish-list</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (($students ?? []) as $student): ?>
            <tr>
              <td><?= htmlspecialchars(trim((string) $student['prenom'] . ' ' . (string) $student['nom']), ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars((string) $student['email'], ENT_QUOTES) ?></td>
              <td><?= (int) ($student['applications_count'] ?? 0) ?></td>
              <td><?= (int) ($student['pending_count'] ?? 0) ?></td>
              <td><?= (int) ($student['wishlist_count'] ?? 0) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </article>

  <aside class="side-card">
    <h2 class="side-card-title">Candidatures récentes</h2>
    <ul class="list-compact">
      <?php foreach (array_slice($applications ?? [], 0, 5) as $application): ?>
        <li>
          <span><?= htmlspecialchars(trim((string) $application['etudiant_prenom'] . ' ' . (string) $application['etudiant_nom']), ENT_QUOTES) ?> · <?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></span>
          <strong><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></strong>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>
</section>

<?php if (!empty($applications)): ?>
  <section class="dashboard-grid">
    <?php foreach (array_slice($applications ?? [], 0, 6) as $application): ?>
      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title">
            <?= htmlspecialchars(trim((string) $application['etudiant_prenom'] . ' ' . (string) $application['etudiant_nom']), ENT_QUOTES) ?>
          </span>
          <span class="pill-small"><?= htmlspecialchars((string) $application['statut'], ENT_QUOTES) ?></span>
        </header>
        <ul class="list-compact">
          <li>
            <span>Offre</span>
            <strong><?= htmlspecialchars((string) $application['titre'], ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Entreprise</span>
            <strong><?= htmlspecialchars((string) $application['entreprise_nom'], ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>Email</span>
            <strong><?= htmlspecialchars((string) $application['etudiant_email'], ENT_QUOTES) ?></strong>
          </li>
          <li>
            <span>CV</span>
            <strong>
              <?php if (!empty($application['cv_path'])): ?>
                <a href="<?= htmlspecialchars(\Core\Url::asset((string) $application['cv_path']), ENT_QUOTES) ?>" target="_blank" rel="noreferrer">
                  Ouvrir le CV
                </a>
              <?php else: ?>
                Non fourni
              <?php endif; ?>
            </strong>
          </li>
        </ul>
        <section class="detail-section detail-section--compact">
          <h2>Lettre de motivation</h2>
          <p><?= nl2br(htmlspecialchars((string) ($application['lettre_motivation'] ?: 'Aucune lettre enregistrée.'), ENT_QUOTES)) ?></p>
        </section>
        <?php if (!empty($application['commentaire'])): ?>
          <section class="detail-section detail-section--compact">
            <h2>Commentaire</h2>
            <p><?= nl2br(htmlspecialchars((string) $application['commentaire'], ENT_QUOTES)) ?></p>
          </section>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>
<?php endif; ?>
