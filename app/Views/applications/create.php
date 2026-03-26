<?php
$hasStoredCv = !empty($documents['cv_path']);
$offerSkills = is_array($offer['skills'] ?? null) ? $offer['skills'] : [];
?>
<header class="page-heading">
  <div class="page-heading-block">
    <span class="page-heading-kicker">Postuler</span>
    <h1 class="page-heading-title">Candidater à <?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h1>
    <p class="page-heading-subtitle">
      Finalisez votre dossier dans une interface plus claire, puis envoyez votre candidature en une seule étape.
    </p>
  </div>
</header>

<section class="journey-layout">
  <article class="journey-main-card">
    <div class="journey-card-head">
      <div>
        <span class="journey-card-kicker">Candidature</span>
        <h2 class="journey-card-title">Mon dossier pour cette offre</h2>
        <p class="journey-card-text">
          Chargez votre CV, adaptez votre lettre de motivation et ajoutez si besoin une note complémentaire.
        </p>
      </div>
      <div class="journey-meta-row">
        <span class="journey-meta-chip"><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></span>
        <span class="journey-meta-chip"><?= htmlspecialchars((string) ($offer['city'] ?: 'Ville non précisée'), ENT_QUOTES) ?></span>
        <span class="journey-meta-chip"><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></span>
      </div>
    </div>

    <form
      method="post"
      action="<?= htmlspecialchars(\Core\Url::route('candidatures'), ENT_QUOTES) ?>"
      enctype="multipart/form-data"
      data-js-validate
    >
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) $csrfToken, ENT_QUOTES) ?>" />
      <input type="hidden" name="offer_id" value="<?= (int) $offer['id'] ?>" />
      <input type="hidden" name="existing_cv_path" value="<?= htmlspecialchars((string) ($documents['cv_path'] ?? ''), ENT_QUOTES) ?>" />

      <div class="journey-form-stack">
        <section class="journey-form-block">
          <div class="journey-form-head">
            <h3>Mon CV</h3>
            <p>Importez votre version la plus récente ou réutilisez le document déjà stocké.</p>
          </div>

          <?php if ($hasStoredCv): ?>
            <p class="journey-inline-note">
              CV enregistré :
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
              <?= $hasStoredCv ? '' : 'required' ?>
            />
            <p class="auth-hint offer-form-hint">Formats acceptés : PDF, DOC, DOCX. Taille maximale : 5 Mo.</p>
          </div>
        </section>

        <section class="journey-form-block">
          <div class="journey-form-head">
            <h3>Lettre de motivation</h3>
            <p>Personnalisez votre message pour l’entreprise et le contexte du stage.</p>
          </div>

          <div class="form-group form-group--full">
            <label for="lettre_motivation">Mon texte</label>
            <textarea
              id="lettre_motivation"
              name="lettre_motivation"
              class="form-control form-control--textarea"
              required
              minlength="30"
            ><?= htmlspecialchars((string) ($documents['lettre_type'] ?? ''), ENT_QUOTES) ?></textarea>
          </div>
        </section>

        <section class="journey-form-block">
          <div class="journey-form-head">
            <h3>Note complémentaire</h3>
            <p>Ajoutez un détail utile à votre dossier si vous le souhaitez.</p>
          </div>

          <div class="form-group form-group--full">
            <label for="commentaire">Commentaire</label>
            <textarea
              id="commentaire"
              name="commentaire"
              class="form-control form-control--textarea"
              placeholder="Informations complémentaires à joindre à votre dossier."
            ></textarea>
          </div>
        </section>
      </div>

      <div class="form-footer offer-form-actions">
        <a href="<?= htmlspecialchars(\Core\Url::route('offres/detail?id=' . (int) $offer['id']), ENT_QUOTES) ?>" class="btn btn-outline">Retour à l’offre</a>
        <button type="submit" class="btn btn-primary">Envoyer la candidature</button>
      </div>
    </form>
  </article>

  <aside class="journey-side-stack">
    <article class="journey-side-card journey-side-card--accent">
      <span class="journey-card-kicker">Offre ciblée</span>
      <h2 class="journey-side-title"><?= htmlspecialchars((string) $offer['title'], ENT_QUOTES) ?></h2>
      <p class="journey-side-text"><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?> - <?= htmlspecialchars((string) ($offer['city'] ?: 'Ville non précisée'), ENT_QUOTES) ?></p>

      <ul class="journey-summary-list">
        <li><span>Entreprise</span><strong><?= htmlspecialchars((string) $offer['company'], ENT_QUOTES) ?></strong></li>
        <li><span>Durée</span><strong><?= htmlspecialchars((string) $offer['duration'], ENT_QUOTES) ?></strong></li>
        <li><span>Rémunération</span><strong><?= htmlspecialchars((string) $offer['salary'], ENT_QUOTES) ?></strong></li>
      </ul>

      <?php if ($offerSkills !== []): ?>
        <div class="offer-skills">
          <?php foreach ($offerSkills as $skill): ?>
            <span class="tag"><?= htmlspecialchars((string) $skill, ENT_QUOTES) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>

    <article class="journey-side-card">
      <span class="journey-card-kicker">Avant l’envoi</span>
      <h2 class="journey-side-title">Checklist rapide</h2>
      <ul class="journey-checklist">
        <li>Vérifier que le CV est bien à jour.</li>
        <li>Adapter la lettre à l’entreprise et à la mission.</li>
        <li>Rester clair, direct et concret dans le commentaire.</li>
      </ul>
    </article>
  </aside>
</section>
