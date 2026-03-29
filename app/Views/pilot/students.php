<?php
$selectedStudent = is_array($selectedStudent ?? null) ? $selectedStudent : null;
$selectedStudentId = (int) ($selectedStudentId ?? 0);
$isNewStudent = (bool) ($isNewStudent ?? false);
$students = is_array($students ?? null) ? $students : [];
$applicationsTotal = array_sum(array_map(static fn (array $student): int => (int) ($student['applications_count'] ?? 0), $students));
$wishlistTotal = array_sum(array_map(static fn (array $student): int => (int) ($student['wishlist_count'] ?? 0), $students));
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Étudiants</span>
    <h1 class="page-heading-title">Gestion des comptes étudiants</h1>
    <p class="page-heading-subtitle">
      Créez, modifiez ou supprimez les comptes étudiants suivis par le pilote.
    </p>
  </div>
</header>

<section class="dashboard-grid dashboard-grid--summary">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Étudiants</span>
      <span class="pill-small"><?= count($students) ?></span>
    </header>
    <p class="action-card-text">Profils actuellement gérés depuis l’espace pilote.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Candidatures</span>
      <span class="pill-small"><?= $applicationsTotal ?></span>
    </header>
    <p class="action-card-text">Volume cumulé des candidatures de la liste étudiante.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">&#9829; Wish-lists</span>
      <span class="pill-small"><?= $wishlistTotal ?></span>
    </header>
    <p class="action-card-text">Nombre total d’offres sauvegardées par les étudiants.</p>
  </article>
</section>

<?php
$paginationCurrentPage = (int) ($currentPage ?? 1);
$paginationTotalPages = (int) ($totalPages ?? 1);
$paginationPageParam = 'page';
$paginationLabel = 'Pagination des étudiants';
require __DIR__ . '/../partials/pagination.php';
?>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Comptes disponibles</h2>
    <p class="side-card-text">
      Sélectionnez un étudiant pour modifier son profil ou créez un nouveau compte.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewStudent ? ' management-offer-link--active' : '' ?>">
        <strong>Nouvel étudiant</strong>
        <span>Ajouter un nouveau compte étudiant</span>
      </a>
      <a href="<?= htmlspecialchars(\Core\Url::route('pilote/relances'), ENT_QUOTES) ?>" class="management-offer-link">
        <strong>Voir les relances</strong>
        <span>Consulter le suivi des candidatures</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach ($students as $student): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants?id=' . (int) $student['id_utilisateur']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $student['id_utilisateur'] === $selectedStudentId && !$isNewStudent ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars(trim((string) $student['prenom'] . ' ' . (string) $student['nom']), ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) $student['email'], ENT_QUOTES) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title"><?= $isNewStudent ? 'Nouveau compte' : 'Édition du compte' ?></span>
      <span class="pill-small"><?= $isNewStudent ? 'Création' : 'Mise à jour' ?></span>
    </header>

    <?php if ($selectedStudent === null): ?>
      <p class="auth-hint">Aucun compte étudiant n’est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_utilisateur" value="<?= (int) ($selectedStudent['id_utilisateur'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars((string) ($selectedStudent['prenom'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars((string) ($selectedStudent['nom'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group form-group--full">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars((string) ($selectedStudent['email'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group form-group--full">
            <label for="mot_de_passe"><?= $isNewStudent ? 'Mot de passe' : 'Nouveau mot de passe' ?></label>
            <input
              type="password"
              id="mot_de_passe"
              name="mot_de_passe"
              class="form-control"
              minlength="8"
              <?= $isNewStudent ? 'required' : '' ?>
            />
            <p class="auth-hint offer-form-hint">
              <?= $isNewStudent ? 'Minimum 8 caractères.' : 'Laissez vide pour conserver le mot de passe actuel.' ?>
            </p>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-pilote'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewStudent ? 'Créer le compte' : 'Enregistrer les modifications' ?></button>
          </div>
          <?php if (!$isNewStudent && (int) ($selectedStudent['id_utilisateur'] ?? 0) > 0): ?>
            <button
              type="submit"
              formaction="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants/supprimer'), ENT_QUOTES) ?>"
              formmethod="post"
              class="btn btn-outline btn-outline--danger"
              onclick="return confirm('Supprimer ce compte étudiant ?');"
            >
              Supprimer le compte
            </button>
          <?php endif; ?>
        </div>
      </form>
    <?php endif; ?>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Vue d’ensemble</h2>
      <p class="section-subtitle">Résumé des comptes étudiants et de leur activité.</p>
    </div>
  </div>

  <div class="table-shell">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Candidatures</th>
          <th>&#9829; Wish-list</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student): ?>
          <tr>
            <td>
              <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants?id=' . (int) $student['id_utilisateur']), ENT_QUOTES) ?>">
                <?= htmlspecialchars(trim((string) $student['prenom'] . ' ' . (string) $student['nom']), ENT_QUOTES) ?>
              </a>
            </td>
            <td><?= htmlspecialchars((string) $student['email'], ENT_QUOTES) ?></td>
            <td><?= (int) ($student['applications_count'] ?? 0) ?></td>
            <td><?= (int) ($student['wishlist_count'] ?? 0) ?></td>
            <td>
              <div class="table-actions">
                <a href="<?= htmlspecialchars(\Core\Url::route('pilote/etudiants?id=' . (int) $student['id_utilisateur']), ENT_QUOTES) ?>" class="btn btn-outline">Modifier</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

