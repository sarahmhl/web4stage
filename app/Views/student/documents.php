<?php
$hasStoredCv = !empty($documents['cv_path']);
$hasStoredLetter = !empty($documents['lettre_type']);
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Documents</span>
    <h1 class="page-heading-title">Mes documents de candidature</h1>
    <p class="page-heading-subtitle">
      Centralisez votre CV et votre lettre type dans un espace simple et clair.
    </p>
  </div>
</header>

<section class="documents-simple-layout">
  <article class="dash-card documents-simple-card">
    <div class="documents-simple-head">
      <div>
        <span class="pill-small">Dossier candidat</span>
        <h2 class="documents-simple-title">Mettre à jour mes documents</h2>
        <p class="documents-simple-text">
          Gardez ici une base propre et à jour pour candidater plus rapidement.
        </p>
      </div>
    </div>

    <p class="documents-simple-summary">
      <?= $hasStoredCv ? 'CV prêt' : 'CV manquant' ?>
      <span>•</span>
      <?= $hasStoredLetter ? 'Lettre type disponible' : 'Lettre type à rédiger' ?>
    </p>

    <form
      method="post"
      action="<?= htmlspecialchars(\Core\Url::route('etudiant/documents'), ENT_QUOTES) ?>"
      enctype="multipart/form-data"
      data-js-validate
      class="documents-simple-form"
    >
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
      <input type="hidden" name="existing_cv_path" value="<?= htmlspecialchars((string) ($documents['cv_path'] ?? ''), ENT_QUOTES) ?>" />

      <section class="documents-simple-block">
        <h3>CV</h3>
        <p class="documents-simple-help">Ajoutez une version récente, lisible et facile à partager.</p>

        <?php if ($hasStoredCv): ?>
          <p class="documents-simple-current">
            CV actuel :
            <a href="<?= htmlspecialchars(\Core\Url::asset((string) $documents['cv_path']), ENT_QUOTES) ?>" target="_blank" rel="noreferrer">
              <?= htmlspecialchars((string) basename((string) $documents['cv_path']), ENT_QUOTES) ?>
            </a>
          </p>
        <?php endif; ?>

        <div class="form-group form-group--full">
          <label for="cv_file">Ajouter ou remplacer mon CV</label>
          <input
            type="file"
            id="cv_file"
            name="cv_file"
            class="form-control"
            accept=".pdf,.doc,.docx"
            data-max-bytes="5242880"
          />
          <p class="auth-hint offer-form-hint">Formats acceptés : PDF, DOC, DOCX. Taille maximale : 5 Mo.</p>
        </div>
      </section>

      <section class="documents-simple-block">
        <h3>Lettre type</h3>
        <p class="documents-simple-help">Conservez un texte de base que vous pourrez adapter ensuite selon l'entreprise.</p>

        <div class="form-group form-group--full">
          <label for="letter_template">Mon texte de base</label>
          <textarea
            id="letter_template"
            name="letter_template"
            class="form-control form-control--textarea"
            required
            minlength="20"
          ><?= htmlspecialchars((string) ($documents['lettre_type'] ?? ''), ENT_QUOTES) ?></textarea>
        </div>
      </section>

      <div class="form-footer documents-simple-footer">
        <button type="submit" class="btn btn-primary">Enregistrer mes documents</button>
      </div>
    </form>
  </article>
</section>
