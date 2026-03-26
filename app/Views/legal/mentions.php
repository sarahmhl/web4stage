<section class="section" aria-labelledby="titre-mentions">
  <div class="section-header">
    <div>
      <h1 class="section-title" id="titre-mentions">Mentions légales</h1>
      <p class="section-subtitle">
        Informations générales sur l'application Web4Stage et sur son cadre d'utilisation.
      </p>
    </div>
  </div>

  <article class="side-card">
    <h2 class="side-card-title">Éditeur du site</h2>
    <p class="side-card-text">
      Web4Stage est une application web réalisée dans le cadre d'un projet pédagogique CESI autour de la gestion
      des offres de stage, des candidatures et du suivi des utilisateurs.
    </p>

    <h2 class="side-card-title">Responsable du projet</h2>
    <p class="side-card-text">
      <?= htmlspecialchars((string) ($legalOwner ?? 'Équipe projet Web4Stage - CESI'), ENT_QUOTES) ?>
    </p>

    <h2 class="side-card-title">Contact</h2>
    <p class="side-card-text">
      Pour toute question relative au projet, vous pouvez contacter l'équipe via
      <a href="mailto:<?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>">
        <?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>
      </a>.
    </p>

    <h2 class="side-card-title">Hébergement</h2>
    <p class="side-card-text">
      <?= htmlspecialchars((string) ($legalHosting ?? ''), ENT_QUOTES) ?>
    </p>

    <h2 class="side-card-title">Propriété intellectuelle</h2>
    <p class="side-card-text">
      Les contenus, maquettes, interfaces, textes et éléments techniques présentés dans cette application sont
      utilisés pour la démonstration du projet. Toute réutilisation doit rester conforme au cadre pédagogique et
      citer le projet lorsqu'elle est partagée.
    </p>

    <h2 class="side-card-title">Données personnelles</h2>
    <p class="side-card-text">
      Le projet manipule des comptes et données de démonstration afin d'illustrer des parcours de candidature.
      Les données ne sont pas destinées à un usage commercial réel. Une politique de confidentialité est disponible
      pour décrire plus précisément leur traitement.
    </p>
  </article>
</section>
