<?php
// Vue du formulaire d'avis sur la formation et l'accompagnement.
$feedbacks = is_array($feedbacks ?? null) ? $feedbacks : [];
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Avis sur la formation</span>
    <h1 class="page-heading-title">Donner son avis sur la formation</h1>
    <p class="page-heading-subtitle">
      Partagez un retour sur l'accompagnement pédagogique, le suivi du pilote et l'aide apportée dans votre recherche de stage.
    </p>
  </div>
</header>

<section class="section" aria-labelledby="section-form-avis">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-form-avis">Publier un avis</h2>
      <p class="section-subtitle">Un retour clair et concret suffit.</p>
    </div>
  </div>

  <article class="dash-card">
    <header class="dash-card-header">
      <span class="dash-card-title dash-card-title--icon">
        <span class="card-title-icon" aria-hidden="true">★</span>
        <span>Mon retour sur la formation</span>
      </span>
      <span class="pill-small">Formation</span>
    </header>

    <p class="side-card-text">
      Vous pouvez parler du suivi pédagogique, de l'accompagnement du pilote, de la clarté des consignes et de l'aide reçue pour trouver un stage.
    </p>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('etudiant/avis'), ENT_QUOTES) ?>" data-js-validate>
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
          <textarea
            id="comment"
            name="comment"
            class="form-control form-control--textarea"
            placeholder="Expliquez ce que la formation vous apporte concrètement pour préparer votre recherche de stage."
            required
          ></textarea>
        </div>
      </div>

      <p class="auth-hint">Conseil : restez simple, précis et utile pour les autres étudiants.</p>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary">Publier mon avis</button>
      </div>
    </form>
  </article>
</section>

<section class="section" aria-labelledby="section-liste-avis">
  <div class="section-header">
    <div>
      <h2 class="section-title" id="section-liste-avis">Avis déjà publiés</h2>
      <p class="section-subtitle">Retours récents des étudiants sur la formation.</p>
    </div>
  </div>

  <?php if ($feedbacks !== []): ?>
    <div class="dashboard-grid">
      <?php foreach ($feedbacks as $feedback): ?>
        <article class="dash-card">
          <header class="dash-card-header">
            <span class="dash-card-title"><?= htmlspecialchars(trim((string) $feedback['prenom'] . ' ' . (string) $feedback['nom']), ENT_QUOTES) ?></span>
            <span class="pill-small"><?= (int) ($feedback['note'] ?? 0) ?>/5</span>
          </header>
          <p class="action-card-text"><?= nl2br(htmlspecialchars((string) $feedback['commentaire'], ENT_QUOTES)) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <strong>Aucun avis</strong>
      <p class="empty-state-text">Le premier retour sur la formation peut être publié depuis le formulaire ci-dessus.</p>
    </div>
  <?php endif; ?>
</section>
