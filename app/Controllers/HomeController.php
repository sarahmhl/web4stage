<?php

declare(strict_types=1);

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

        try {
            $overviewStats = Offer::overviewStats();
        } catch (\Throwable $e) {
            $overviewStats = [
                'offers' => 0,
                'companies' => 0,
                'cities' => 0,
                'skills' => 0,
            ];
        }

        View::render('home', [
            'title' => 'Web4Stage - Portail de stages',
            'popularOffers' => $popularOffers,
            'studentReviews' => $studentReviews,
            'overviewStats' => $overviewStats,
            'metaDescription' => 'Plateforme de recherche de stages, candidatures et suivi pédagogique pour les étudiants CESI.',
            'metaKeywords' => 'stage, cesi, portail de stages, candidatures, web4stage',
        ]);
    }
}
