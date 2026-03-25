<section class="section" aria-labelledby="titre-mentions">
  <div class="section-header">
    <div>
      <h1 class="section-title" id="titre-mentions">Mentions legales</h1>
      <p class="section-subtitle">
        Informations generales sur l application Web4Stage et sur son cadre d utilisation.
      </p>
    </div>
  </div>

  <article class="side-card">
    <h2 class="side-card-title">Editeur du site</h2>
    <p class="side-card-text">
      Web4Stage est une application web realisee dans le cadre d un projet pedagogique CESI autour de la gestion
      des offres de stage, des candidatures et du suivi des utilisateurs.
    </p>

    <h2 class="side-card-title">Responsable du projet</h2>
    <p class="side-card-text">
      <?= htmlspecialchars((string) ($legalOwner ?? 'Equipe projet Web4Stage - CESI'), ENT_QUOTES) ?>
    </p>

    <h2 class="side-card-title">Contact</h2>
    <p class="side-card-text">
      Pour toute question relative au projet, vous pouvez contacter l equipe via
      <a href="mailto:<?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>">
        <?= htmlspecialchars((string) ($legalContactEmail ?? 'contact@web4stage.local'), ENT_QUOTES) ?>
      </a>.
    </p>

    <h2 class="side-card-title">Hebergement</h2>
    <p class="side-card-text">
      <?= htmlspecialchars((string) ($legalHosting ?? ''), ENT_QUOTES) ?>
    </p>

    <h2 class="side-card-title">Propriete intellectuelle</h2>
    <p class="side-card-text">
      Les contenus, maquettes, interfaces, textes et elements techniques presentes dans cette application sont
      utilises pour la demonstration du projet. Toute reutilisation doit rester conforme au cadre pedagogique et
      citer le projet lorsqu elle est partagee.
    </p>

    <h2 class="side-card-title">Donnees personnelles</h2>
    <p class="side-card-text">
      Le projet manipule des comptes et donnees de demonstration afin d illustrer des parcours de candidature.
      Les donnees ne sont pas destinees a un usage commercial reel. Une politique de confidentialite est disponible
      pour decrire plus precisement leur traitement.
    </p>
  </article>
</section>
