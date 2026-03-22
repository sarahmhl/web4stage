<?php

declare(strict_types=1);

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

