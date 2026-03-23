<?php // Vue etudiant pour enregistrer son CV et sa lettre de motivation type. ?>
<?php $hasStoredCv = !empty($documents['cv_path']); ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Documents</span>
    <h1 class="page-heading-title">Mes documents de candidature</h1>
    <p class="page-heading-subtitle">
      Gardez votre CV et votre lettre type prêts pour postuler plus vite.
    </p>
  </div>
</header>

<section class="page-layout detail-layout">
  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Mettre à jour mes documents</span>
      <span class="pill-small">CV + lettre type</span>
    </header>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>" enctype="multipart/form-data">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
      <input type="hidden" name="existing_cv_path" value="<?= htmlspecialchars((string) ($documents['cv_path'] ?? ''), ENT_QUOTES) ?>" />
      <div class="offer-form-grid">
        <div class="form-group form-group--full">
          <label for="cv_file">CV</label>
          <?php if ($hasStoredCv): ?>
            <p class="auth-hint offer-form-hint">
              CV actuel :
              <a href="<?= htmlspecialchars(\Core\Url::asset((string) $documents['cv_path']), ENT_QUOTES) ?>" target="_blank" rel="noreferrer">
                <?= htmlspecialchars((string) basename((string) $documents['cv_path']), ENT_QUOTES) ?>
              </a>
            </p>
          <?php endif; ?>
          <input
            type="file"
            id="cv_file"
            name="cv_file"
            class="form-control"
            accept=".pdf,.doc,.docx"
          />
          <p class="auth-hint offer-form-hint">Formats acceptes : PDF, DOC, DOCX. Taille maximale : 5 Mo.</p>
        </div>
        <div class="form-group form-group--full">
          <label for="letter_template">Lettre de motivation type</label>
          <textarea id="letter_template" name="letter_template" class="form-control form-control--textarea"><?= htmlspecialchars((string) ($documents['lettre_type'] ?? ''), ENT_QUOTES) ?></textarea>
        </div>
      </div>
      <div class="form-footer offer-form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer mes documents</button>
      </div>
    </form>
  </article>

  <aside class="side-card">
    <h2 class="side-card-title">Astuce</h2>
    <p class="side-card-text">
      Garde ici une version claire de ton CV et une lettre type à personnaliser ensuite depuis chaque candidature.
    </p>
  </aside>
</section>
