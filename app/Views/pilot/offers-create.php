<?php
  $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
  $routePrefix = rtrim($scriptName, '/');
  $routeUrl = static function (string $path) use ($routePrefix): string {
    return htmlspecialchars(str_replace(' ', '%20', $routePrefix . '/' . ltrim($path, '/')), ENT_QUOTES);
  };
  $old = is_array($old ?? null) ? $old : [];
  $value = static function (string $key, string $default = '') use ($old): string {
    $raw = $old[$key] ?? $default;
    return is_scalar($raw) ? (string) $raw : $default;
  };
?>
<header class="dashboard-header">
  <div class="dashboard-title-block">
    <h1 class="dashboard-title">Ajouter une offre de stage</h1>
    <p class="dashboard-subtitle">
      Creez une nouvelle offre pilotee par votre promotion avec son entreprise, ses competences et son illustration.
    </p>
  </div>
  <span class="pill-role">Role : Pilote</span>
</header>

<?php if (!empty($success)): ?>
  <p class="auth-hint auth-hint--success"><?= htmlspecialchars($success, ENT_QUOTES) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <p class="auth-hint auth-hint--error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
<?php endif; ?>

<section class="page-layout offer-management-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Avant de publier</h2>
    <p class="side-card-text">
      Verifiez que le titre est clair, que la duree est precisee et que les competences sont separees par des virgules.
    </p>
    <div class="stat-row">
      <div class="stat-pill">
        <span>Exemple</span>
        <strong>PHP, MVC, MySQL</strong>
      </div>
      <div class="stat-pill">
        <span>Statut</span>
        <strong>Publication immediate</strong>
      </div>
      <div class="stat-pill">
        <span>Image</span>
        <strong>Choix guide</strong>
      </div>
    </div>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Nouvelle offre</span>
      <span class="pill-small">Ajout pilote</span>
    </header>

    <form method="post" action="<?= $routeUrl('pilote/offres/ajouter') ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />

      <div class="offer-form-grid">
        <div class="form-group">
          <label for="id_entreprise">Entreprise</label>
          <select id="id_entreprise" name="id_entreprise" class="form-control" required>
            <option value="">Selectionner une entreprise</option>
            <?php foreach ($companies as $company): ?>
              <option
                value="<?= (int) $company['id'] ?>"
                <?= $value('id_entreprise') === (string) $company['id'] ? 'selected' : '' ?>
              >
                <?= htmlspecialchars((string) $company['nom'], ENT_QUOTES) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="titre">Titre du stage</label>
          <input
            type="text"
            id="titre"
            name="titre"
            class="form-control"
            value="<?= htmlspecialchars($value('titre'), ENT_QUOTES) ?>"
            placeholder="Ex : Stage Developpeur Web PHP / JS"
            required
          />
        </div>

        <div class="form-group">
          <label for="date_offre">Date de publication</label>
          <input
            type="date"
            id="date_offre"
            name="date_offre"
            class="form-control"
            value="<?= htmlspecialchars($value('date_offre', date('Y-m-d')), ENT_QUOTES) ?>"
            required
          />
        </div>

        <div class="form-group">
          <label for="duree_mois">Duree en mois</label>
          <input
            type="number"
            id="duree_mois"
            name="duree_mois"
            class="form-control"
            min="1"
            value="<?= htmlspecialchars($value('duree_mois'), ENT_QUOTES) ?>"
            placeholder="Ex : 6"
          />
        </div>

        <div class="form-group">
          <label for="base_remuneration">Base de remuneration</label>
          <input
            type="number"
            step="0.01"
            id="base_remuneration"
            name="base_remuneration"
            class="form-control"
            value="<?= htmlspecialchars($value('base_remuneration'), ENT_QUOTES) ?>"
            placeholder="Ex : 900"
          />
        </div>

        <div class="form-group">
          <label for="image_path">Illustration</label>
          <select id="image_path" name="image_path" class="form-control">
            <option value="">Selectionner une image</option>
            <?php foreach ($imageOptions as $option): ?>
              <option
                value="<?= htmlspecialchars($option['file'], ENT_QUOTES) ?>"
                <?= $value('image_path') === $option['file'] ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($option['label'], ENT_QUOTES) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group form-group--full">
          <label for="skills">Competences</label>
          <input
            type="text"
            id="skills"
            name="skills"
            class="form-control"
            value="<?= htmlspecialchars($value('skills'), ENT_QUOTES) ?>"
            placeholder="Ex : PHP, MVC, MySQL"
          />
          <p class="auth-hint offer-form-hint">Separez les competences avec des virgules.</p>
        </div>

        <div class="form-group form-group--full">
          <label for="description">Description</label>
          <textarea
            id="description"
            name="description"
            class="form-control form-control--textarea"
            placeholder="Decrivez la mission, le contexte et les attentes du stage."
            required
          ><?= htmlspecialchars($value('description'), ENT_QUOTES) ?></textarea>
        </div>
      </div>

      <div class="form-footer offer-form-actions">
        <a href="<?= $routeUrl('dashboard-pilote') ?>" class="btn btn-outline">Retour au tableau de bord</a>
        <button type="submit" class="btn btn-primary">Ajouter le stage</button>
      </div>
    </form>
  </article>
</section>
