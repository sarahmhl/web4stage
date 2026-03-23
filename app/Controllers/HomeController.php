<?php

declare(strict_types=1);

// Ce controleur prepare les donnees de la page d accueil publique : offres mises en avant, avis et statistiques.

namespace App\Controllers;

use App\Models\Offer;
use App\Models\StudentFeedback;
use Core\View;

class HomeController extends BaseController
{
    public function index(): void
    {
        try {
            $popularOffers = Offer::search([], 3, 0);
        } catch (\Throwable $e) {
            $popularOffers = [];
        }

        try {
            $studentReviews = StudentFeedback::latest(3);
        } catch (\Throwable $e) {
            $studentReviews = [];
        }

        View::render('home', [
            'title' => 'Web4Stage - Portail de stages',
            'popularOffers' => $popularOffers,
            'studentReviews' => $studentReviews,
        ]);
    }
}

