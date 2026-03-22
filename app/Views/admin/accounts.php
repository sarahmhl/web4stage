<?php // Vue admin de gestion des utilisateurs et de leurs roles. ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Comptes</span>
    <h1 class="page-heading-title">Gestion des utilisateurs</h1>
    <p class="page-heading-subtitle">
      Vue centralisée des comptes étudiants, pilotes et administrateurs.
    </p>
  </div>
</header>

<section class="dash-card">
  <div class="table-shell">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Candidatures</th>
          <th>Wish-list</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($users ?? []) as $user): ?>
          <tr>
            <td><?= htmlspecialchars(trim((string) $user['prenom'] . ' ' . (string) $user['nom']), ENT_QUOTES) ?></td>
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
