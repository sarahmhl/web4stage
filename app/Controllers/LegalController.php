<?php
// Affiche les pages legales comme les mentions et la confidentialite.

declare(strict_types=1);


namespace App\Controllers;

use Core\Config;
use Core\View;

class LegalController
{
    public function mentions(): void
    {
        View::render('legal/mentions', [
            'title' => 'Web4Stage - Mentions légales',
            'legalOwner' => (string) Config::get('legal_owner', 'Équipe projet Web4Stage - CESI'),
            'legalContactEmail' => (string) Config::get('legal_contact_email', 'contact@web4stage.local'),
            'legalHosting' => (string) Config::get('legal_hosting', ''),
            'metaDescription' => 'Mentions légales du projet pédagogique Web4Stage réalisé dans le cadre de la formation CESI.',
            'metaKeywords' => 'mentions légales, cesi, web4stage, projet pédagogique',
        ]);
    }

    public function privacy(): void
    {
        View::render('legal/privacy', [
            'title' => 'Web4Stage - Politique de confidentialité',
            'legalContactEmail' => (string) Config::get('legal_contact_email', 'contact@web4stage.local'),
            'metaDescription' => 'Politique de confidentialité et traitement des données du projet Web4Stage.',
            'metaKeywords' => 'confidentialité, données personnelles, rgpd, web4stage',
        ]);
    }
}
