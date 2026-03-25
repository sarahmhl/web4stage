<?php

declare(strict_types=1);


namespace App\Controllers;

use Core\Config;
use Core\View;

class LegalController
{
    public function mentions(): void
    {
        View::render('legal/mentions', [
            'title' => 'Web4Stage - Mentions legales',
            'legalOwner' => (string) Config::get('legal_owner', 'Equipe projet Web4Stage - CESI'),
            'legalContactEmail' => (string) Config::get('legal_contact_email', 'contact@web4stage.local'),
            'legalHosting' => (string) Config::get('legal_hosting', ''),
            'metaDescription' => 'Mentions legales du projet pedagogique Web4Stage realise dans le cadre de la formation CESI.',
            'metaKeywords' => 'mentions legales, cesi, web4stage, projet pedagogique',
        ]);
    }

    public function privacy(): void
    {
        View::render('legal/privacy', [
            'title' => 'Web4Stage - Politique de confidentialite',
            'legalContactEmail' => (string) Config::get('legal_contact_email', 'contact@web4stage.local'),
            'metaDescription' => 'Politique de confidentialite et traitement des donnees du projet Web4Stage.',
            'metaKeywords' => 'confidentialite, donnees personnelles, rgpd, web4stage',
        ]);
    }
}
