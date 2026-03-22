<?php // Vue du formulaire de candidature a une offre avec CV et lettre de motivation. ?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Postuler</span>
    <h1 class="page-heading-title">Candidater à <?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      Complétez votre lettre de motivation et indiquez le CV à joindre à votre candidature.
    </p>
  </div>
</header>

<section class="page-layout">
  <aside class="side-card">
    <h2 class="side-card-title">Rappel de l offre</h2>
    <ul class="list-compact">
      <li>
        <span>Entreprise</span>
        <strong><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></strong>
      </li>
      <li>
        <span>Ville</span>
        <strong><?= htmlspecialchars((string) ($offer['city'] ?: 'Non précisée'), ENT_QUOTES) ?></strong>
      </li>
      <li>
        <span>Durée</span>
        <strong><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></strong>
      </li>
      <li>
        <span>Rémunération</span>
        <strong><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></strong>
      </li>
    </ul>
  </aside>

  <article class="dash-card offer-editor-card">
    <header class="dash-card-header">
      <span class="dash-card-title">Formulaire de candidature</span>
      <span class="pill-small">CV + lettre</span>
    </header>

    <form method="post" action="<?= htmlspecialchars(\Core\Url::route('candidatures'), ENT_QUOTES) ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
      <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />

      <div class="offer-form-grid">
        <div class="form-group form-group--full">
          <label for="cv_path">CV</label>
          <input
            type="text"
            id="cv_path"
            name="cv_path"
            class="form-control"
            value="<?= htmlspecialchars((string) ($documents['cv_path'] ?? ''), ENT_QUOTES) ?>"
            placeholder="Ex : cv/lea-martin-cv.pdf"
            required
          />
        </div>

        <div class="form-group form-group--full">
          <label for="lettre_motivation">Lettre de motivation</label>
          <textarea
            id="lettre_motivation"
            name="lettre_motivation"
            class="form-control form-control--textarea"
            required
          ><?= htmlspecialchars((string) ($documents['lettre_type'] ?? ''), ENT_QUOTES) ?></textarea>
        </div>

        <div class="form-group form-group--full">
          <label for="commentaire">Note complémentaire</label>
          <textarea
            id="commentaire"
            name="commentaire"
            class="form-control form-control--textarea"
            placeholder="Informations complémentaires à joindre à votre dossier."
          ></textarea>
        </div>
      </div>

      <div class="form-footer offer-form-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">Retour à l offre</a>
        <button type="submit" class="btn btn-primary">Envoyer la candidature</button>
      </div>
    </form>
  </article>
</section>
