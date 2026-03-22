<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Avis étudiants</span>
    <h1 class="page-heading-title">Donner son avis sur la formation</h1>
    <p class="page-heading-subtitle">
      Partagez votre retour sur l accompagnement, puis consultez les avis déjà publiés.
    </p>
  </div>
</header>

<section class="page-layout detail-layout">
  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Nouvel avis</span>
      <span class="pill-small">Étudiant</span>
    </header>
    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('etudiant/avis'), ENT_QUOTES) ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
      <div class="offer-form-grid">
        <div class="form-group">
          <label for="rating">Note</label>
          <select id="rating" name="rating" class="form-control" required>
            <option value="">Choisir</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <option value="<?= $i ?>"><?= $i ?>/5</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group form-group--full">
          <label for="comment">Commentaire</label>
          <textarea id="comment" name="comment" class="form-control form-control--textarea" required></textarea>
        </div>
      </div>
      <div class="form-footer offer-form-actions">
        <button type="submit" class="btn btn-primary">Publier mon avis</button>
      </div>
    </form>
  </article>

  <aside class="side-card">
    <h2 class="side-card-title">Conseil</h2>
    <p class="side-card-text">
      Décrivez ce qui vous aide concrètement dans la recherche de stage: clarté des offres, suivi, accompagnement ou ergonomie.
    </p>
  </aside>
</section>

<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Avis déjà publiés</h2>
      <p class="section-subtitle">Retours des étudiants sur la plateforme et l accompagnement.</p>
    </div>
  </div>

  <div class="dashboard-grid">
    <?php foreach (($feedbacks ?? []) as $feedback): ?>
      <article class="dash-card">
        <header class="dash-card-header">
          <span class="dash-card-title"><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
          <span class="pill-small"><?= (int) ($feedback['note'] ?? 0) ?>/5</span>
        </header>
        <p class="action-card-text"><?= nl2br(htmlspecialchars((string) $feedback['commentaire'], ENT_QUOTES)) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>
