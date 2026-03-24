<?php

declare(strict_types=1);

// Ce controleur affiche les mentions legales et les informations obligatoires du site.

namespace App\Controllers;

use Core\View;

class LegalController
{
    public function mentions(): void
    {
        View::render('legal/mentions', [
            'title' => 'Web4Stage - Mentions légales',
        ]);
    }
}


