<section class="section" aria-labelledby="titre-politique">
  <div class="section-header">
    <div>
      <h1 class="section-title" id="titre-politique">Politique de confidentialite</h1>
      <p class="section-subtitle">
        Traitement des donnees de demonstration utilisees dans le projet Web4Stage.
      </p>
    </div>
  </div>

  <article class="side-card">
    <h2 class="side-card-title">Finalite</h2>
    <p class="side-card-text">
      Les donnees presentes dans Web4Stage servent a illustrer la gestion de comptes, d offres, de candidatures,
      d avis et de documents dans un contexte pedagogique.
    </p>

    <h2 class="side-card-title">Donnees stockees</h2>
    <p class="side-card-text">
      Les informations enregistrees peuvent inclure l identite d un utilisateur, son role, ses candidatures, ses
      favoris, ses documents de candidature et ses retours sur la plateforme ou sur une entreprise.
    </p>

    <h2 class="side-card-title">Acces et securite</h2>
    <p class="side-card-text">
      Les acces aux espaces du site sont limites selon le role de l utilisateur. Les connexions a la base sont
      realisees via PDO, les formulaires critiques utilisent un jeton CSRF et les fichiers televerses sont ranges
      dans un dossier dedie non indexe.
    </p>

    <h2 class="side-card-title">Conservation</h2>
    <p class="side-card-text">
      Les donnees restent sur l environnement de demonstration du projet. Elles peuvent etre reinitialisees lors des
      imports SQL et des phases de recette.
    </p>

    <h2 class="side-card-title">Contact</h2>
    <p class="side-card-text">
      Pour toute demande liee aux donnees de demonstration, l equipe projet reste joignable via
      <a href="mailto:<?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>">
        <?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>
      </a>.
    </p>
  </article>
</section>
