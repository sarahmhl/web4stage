<?php

$selectedUser = is_array($selectedUser ?? null) ? $selectedUser : null;
$selectedUserId = (int) ($selectedUserId ?? 0);
$isNewAccount = (bool) ($isNewAccount ?? false);
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Comptes</span>
    <h1 class="page-heading-title">Gestion des utilisateurs</h1>
    <p class="page-heading-subtitle">
      Creez, modifiez ou supprimez les comptes etudiant, pilote et administrateur.
    </p>
  </div>
</header>

<section class="dashboard-grid dashboard-grid--summary">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Etudiants</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_ETUDIANT] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Comptes de recherche de stage et de candidatures.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Pilotes</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_PILOTE] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Comptes pedagogiques pour le suivi de la promotion.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Administrateurs</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_ADMIN] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Acces back-office, moderation et gestion globale.</p>
  </article>
</section>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Comptes disponibles</h2>
    <p class="side-card-text">
      Selectionnez un utilisateur pour modifier son profil ou creez un nouveau compte.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewAccount ? ' management-offer-link--active' : '' ?>">
        <strong>Nouveau compte</strong>
        <span>Ajouter un etudiant, un pilote ou un administrateur</span>
      </a>
    </div>

    <div class="management-offer-list">
      <?php foreach (($users ?? []) as $user): ?>
        <a
          href="<?= htmlspecialchars(\Core\Url::route('admin/comptes?id=' . (int) $user['id_utilisateur']), ENT_QUOTES) ?>"
          class="management-offer-link<?= (int) $user['id_utilisateur'] === $selectedUserId && !$isNewAccount ? ' management-offer-link--active' : '' ?>"
        >
          <strong><?= htmlspecialchars(trim((string) $user['prenom'] . ' ' . (string) $user['nom']), ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars((string) $user['email'], ENT_QUOTES) ?> - <?= htmlspecialchars((string) $user['role'], ENT_QUOTES) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title"><?= $isNewAccount ? 'Nouveau compte' : 'Edition du compte' ?></span>
      <span class="pill-small"><?= $isNewAccount ? 'Creation' : 'Mise a jour' ?></span>
    </header>

    <?php if ($selectedUser === null): ?>
      <p class="auth-hint">Aucun compte n est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_utilisateur" value="<?= (int) ($selectedUser['id_utilisateur'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="prenom">Prenom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars((string) ($selectedUser['prenom'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars((string) ($selectedUser['nom'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars((string) ($selectedUser['email'] ?? ''), ENT_QUOTES) ?>" required />
          </div>

          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
              <?php foreach (($roleOptions ?? []) as $roleValue => $roleLabel): ?>
                <option value="<?= htmlspecialchars((string) $roleValue, ENT_QUOTES) ?>" <?= (string) ($selectedUser['role'] ?? '') === (string) $roleValue ? 'selected' : '' ?>>
                  <?= htmlspecialchars((string) $roleLabel, ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group form-group--full">
            <label for="mot_de_passe"><?= $isNewAccount ? 'Mot de passe' : 'Nouveau mot de passe' ?></label>
            <input
              type="password"
              id="mot_de_passe"
              name="mot_de_passe"
              class="form-control"
              minlength="8"
              <?= $isNewAccount ? 'required' : '' ?>
            />
            <p class="auth-hint offer-form-hint">
              <?= $isNewAccount ? 'Minimum 8 caracteres.' : 'Laissez vide pour conserver le mot de passe actuel.' ?>
            </p>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-admin'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewAccount ? 'Creer le compte' : 'Enregistrer les modifications' ?></button>
          </div>
        </div>
      </form>

      <?php if (!$isNewAccount && (int) ($selectedUser['id_utilisateur'] ?? 0) > 0): ?>
        <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/comptes/supprimer'), ENT_QUOTES) ?>" class="inline-action-form" onsubmit="return confirm('Supprimer ce compte ?');">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
          <input type="hidden" name="id_utilisateur" value="<?= (int) ($selectedUser['id_utilisateur'] ?? 0) ?>" />
          <button type="submit" class="btn btn-outline btn-outline--danger">Supprimer le compte</button>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </article>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Vue d ensemble</h2>
      <p class="section-subtitle">Resume des comptes et de leur activite sur la plateforme.</p>
    </div>
  </div>

  <div class="table-shell">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Role</th>
          <th>Candidatures</th>
          <th>Wish-list</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($users ?? []) as $user): ?>
          <tr>
            <td>
              <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes?id=' . (int) $user['id_utilisateur']), ENT_QUOTES) ?>">
                <?= htmlspecialchars(trim((string) $user['prenom'] . ' ' . (string) $user['nom']), ENT_QUOTES) ?>
              </a>
            </td>
            <td><?= htmlspecialchars((string) $user['email'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars((string) $user['role'], ENT_QUOTES) ?></td>
            <td><?= (int) ($user['applications_count'] ?? 0) ?></td>
            <td><?= (int) ($user['wishlist_count'] ?? 0) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
