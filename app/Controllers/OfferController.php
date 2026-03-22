<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Offer;
use Core\View;

class OfferController
{
    public function index(): void
    {
        // Recuperation des offres depuis la base
        try {
            $offers = Offer::all();
        } catch (\Throwable $e) {
            // Fallback : donnees simulees si la base n'est pas disponible
            $offers = [
                [
                    'badge' => 'TH',
                    'title' => 'Stage Developpeur Front-end',
                    'company' => 'Tech Horizon',
                    'duration' => '6 mois',
                    'salary' => '850 EUR/mois',
                    'published' => '2026-03-19',
                    'image' => 'devfontend.jpeg',
                    'skills' => ['HTML5 / CSS3', 'JavaScript', 'Design system'],
                    'tagline' => '12 candidatures enregistrees cette semaine.',
                ],
                [
                    'badge' => 'NM',
                    'title' => 'Stage Marketing digital',
                    'company' => 'Nova Media',
                    'duration' => '4 mois',
                    'salary' => 'Selon profil',
                    'published' => '2026-03-19',
                    'image' => 'Marketing.jpeg',
                    'skills' => ['Canva', 'Redaction', 'Reseaux sociaux'],
                    'tagline' => 'Offre fortement consultee ce mois-ci.',
                ],
                [
                    'badge' => 'CD',
                    'title' => 'Stage Developpeur PHP / MVC',
                    'company' => 'Cesi Digital',
                    'duration' => '5 mois',
                    'salary' => '900 EUR/mois',
                    'published' => '2026-03-19',
                    'image' => 'devphp.jpeg',
                    'skills' => ['MVC', 'MySQL', 'PHP objet'],
                    'tagline' => 'Mission adaptee aux projets web academiques.',
                ],
                [
                    'badge' => 'AW',
                    'title' => 'Stage Developpeur Web PHP / JS',
                    'company' => 'Altis Web',
                    'duration' => '6 mois',
                    'salary' => '900 EUR/mois',
                    'published' => '2026-02-02',
                    'image' => 'devweb.jpeg',
                    'skills' => ['PHP POO', 'JavaScript', 'MySQL'],
                    'tagline' => '8 candidatures en cours de traitement.',
                ],
                [
                    'badge' => 'SI',
                    'title' => 'Stage UX / UI Designer junior',
                    'company' => 'Studio Interface',
                    'duration' => '4 mois',
                    'salary' => 'Gratification selon profil',
                    'published' => '2026-01-28',
                    'image' => 'design.jpg',
                    'skills' => ['Wireframes', 'Figma', 'Design system'],
                    'tagline' => 'Top des offres en wish-list cette semaine.',
                ],
                [
                    'badge' => 'DI',
                    'title' => 'Stage Data & BI',
                    'company' => 'Data Insight',
                    'duration' => '6 mois',
                    'salary' => '1 000 EUR/mois',
                    'published' => '2026-01-20',
                    'image' => 'devweb.jpeg',
                    'skills' => ['SQL', 'Power BI', 'Reporting'],
                    'tagline' => '4 etudiants de la promo ont deja candidate a ce stage.',
                ],
                [
                    'badge' => 'CE',
                    'title' => 'Stage Communication & Events',
                    'company' => 'Campus Events',
                    'duration' => '3 mois',
                    'salary' => '600 EUR/mois',
                    'published' => '2026-01-15',
                    'image' => 'Marketing.jpeg',
                    'skills' => ['Organisation evenements', 'Communication'],
                    'tagline' => 'Ideal pour un premier stage polyvalent.',
                ],
                [
                    'badge' => 'IS',
                    'title' => 'Stage Admin Systemes & Reseaux',
                    'company' => 'Infra Secure',
                    'duration' => '6 mois',
                    'salary' => '900 EUR/mois',
                    'published' => '2026-01-10',
                    'image' => 'devphp.jpeg',
                    'skills' => ['Linux', 'Securite', 'Scripts'],
                    'tagline' => 'Suivi dedie par un pilote de promo.',
                ],
            ];
        }

        $perPage = 3;
        $totalOffers = count($offers);
        $totalPages = max(1, (int) ceil($totalOffers / $perPage));
        $requestedPage = filter_input(
            INPUT_GET,
            'page',
            FILTER_VALIDATE_INT,
            ['options' => ['default' => 1, 'min_range' => 1]]
        );
        $currentPage = is_int($requestedPage) ? min($requestedPage, $totalPages) : 1;
        $offset = ($currentPage - 1) * $perPage;
        $offers = array_slice($offers, $offset, $perPage);

        View::render('offers/index', [
            'title' => 'Web4Stage - Offres de stage',
            'offers' => $offers,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}
