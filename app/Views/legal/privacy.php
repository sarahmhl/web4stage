<section class="section" aria-labelledby="titre-politique">
  <div class="section-header">
    <div>
      <h1 class="section-title" id="titre-politique">Politique de confidentialité</h1>
      <p class="section-subtitle">
        Traitement des données de démonstration utilisées dans le projet Web4Stage.
      </p>
    </div>
  </div>

  <article class="side-card">
    <h2 class="side-card-title">Finalité</h2>
    <p class="side-card-text">
      Les données présentes dans Web4Stage servent à illustrer la gestion de comptes, d'offres, de candidatures,
      d'avis et de documents dans un contexte pédagogique.
    </p>

    <h2 class="side-card-title">Données stockées</h2>
    <p class="side-card-text">
      Les informations enregistrées peuvent inclure l'identité d'un utilisateur, son rôle, ses candidatures, ses
      favoris, ses documents de candidature et ses retours sur la plateforme ou sur une entreprise.
    </p>

    <h2 class="side-card-title">Accès et sécurité</h2>
    <p class="side-card-text">
      Les accès aux espaces du site sont limités selon le rôle de l'utilisateur. Les connexions à la base sont
      réalisées via PDO, les formulaires critiques utilisent un jeton CSRF et les fichiers téléversés sont rangés
      dans un dossier dédié non indexé.
    </p>

    <h2 class="side-card-title">Conservation</h2>
    <p class="side-card-text">
      Les données restent sur l'environnement de démonstration du projet. Elles peuvent être réinitialisées lors des
      imports SQL et des phases de recette.
    </p>

    <h2 class="side-card-title">Contact</h2>
    <p class="side-card-text">
      Pour toute demande liée aux données de démonstration, l'équipe projet reste joignable via
      <a href="mailto:<?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>">
        <?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>
      </a>.
    </p>
  </article>
</section>
