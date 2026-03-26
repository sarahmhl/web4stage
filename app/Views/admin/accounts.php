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
      Créez, modifiez ou supprimez les comptes étudiant, pilote et administrateur.
    </p>
  </div>
</header>

<section class="dashboard-grid dashboard-grid--summary">
  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Étudiants</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_ETUDIANT] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Comptes de recherche de stage et de candidatures.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Pilotes</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_PILOTE] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Comptes pédagogiques pour le suivi de la promotion.</p>
  </article>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Administrateurs</span>
      <span class="pill-small"><?= (int) ($roleCounts[\Core\Auth::ROLE_ADMIN] ?? 0) ?></span>
    </header>
    <p class="action-card-text">Accès back-office, modération et gestion globale.</p>
  </article>
</section>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Comptes disponibles</h2>
    <p class="side-card-text">
      Sélectionnez un utilisateur pour modifier son profil ou créez un nouveau compte.
    </p>

    <div class="side-card-links">
      <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes?new=1'), ENT_QUOTES) ?>" class="management-offer-link<?= $isNewAccount ? ' management-offer-link--active' : '' ?>">
        <strong>Nouveau compte</strong>
        <span>Ajouter un étudiant, un pilote ou un administrateur</span>
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
      <span class="dash-card-title"><?= $isNewAccount ? 'Nouveau compte' : 'Édition du compte' ?></span>
      <span class="pill-small"><?= $isNewAccount ? 'Création' : 'Mise à jour' ?></span>
    </header>

    <?php if ($selectedUser === null): ?>
      <p class="auth-hint">Aucun compte n’est disponible pour le moment.</p>
    <?php else: ?>
      <form method="post" action="<?= htmlspecialchars(\Core\Url::route('admin/comptes'), ENT_QUOTES) ?>" data-js-validate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES) ?>" />
        <input type="hidden" name="id_utilisateur" value="<?= (int) ($selectedUser['id_utilisateur'] ?? 0) ?>" />

        <div class="offer-form-grid">
          <div class="form-group">
            <label for="prenom">Prénom</label>
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
            <label for="role">Rôle</label>
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
              <?= $isNewAccount ? 'Minimum 8 caractères.' : 'Laissez vide pour conserver le mot de passe actuel.' ?>
            </p>
          </div>
        </div>

        <div class="form-footer offer-form-actions offer-form-actions--split">
          <div class="offer-form-actions-group">
            <a href="<?= htmlspecialchars(\Core\Url::route('dashboard-admin'), ENT_QUOTES) ?>" class="btn btn-outline">Retour au tableau de bord</a>
            <button type="submit" class="btn btn-primary"><?= $isNewAccount ? 'Créer le compte' : 'Enregistrer les modifications' ?></button>
          </div>
          <?php if (!$isNewAccount && (int) ($selectedUser['id_utilisateur'] ?? 0) > 0): ?>
            <button
              type="submit"
              formaction="<?= htmlspecialchars(\Core\Url::route('admin/comptes/supprimer'), ENT_QUOTES) ?>"
              formmethod="post"
              class="btn btn-outline btn-outline--danger"
              onclick="return confirm('Supprimer ce compte ?');"
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
      <p class="section-subtitle">Résumé des comptes et de leur activité sur la plateforme.</p>
    </div>
  </div>

  <div class="table-shell">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Candidatures</th>
          <th>Wish-list</th>
          <th>Actions</th>
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
            <td>
              <div class="table-actions">
                <a href="<?= htmlspecialchars(\Core\Url::route('admin/comptes?id=' . (int) $user['id_utilisateur']), ENT_QUOTES) ?>" class="btn btn-outline">Modifier</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
