<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\View;

class HomeController
{
    public function index(): void
    {
        // Donnees simulees en attendant la connexion complete a la base
        $popularOffers = [
            [
                'badge' => 'TH',
                'title' => 'Stage Developpeur Front-end',
                'company' => 'Tech Horizon - Paris (hybride)',
                'duration' => '6 mois',
                'salary' => '850 EUR/mois',
                'start' => 'Avril',
                'image' => 'devfontend.jpeg',
                'skills' => ['HTML5 / CSS3', 'JavaScript', 'Design system'],
                'tagline' => '12 candidatures enregistrees cette semaine.',
            ],
            [
                'badge' => 'NM',
                'title' => 'Stage Marketing digital',
                'company' => 'Nova Media - Lyon',
                'duration' => '4 a 6 mois',
                'salary' => 'Gratification selon profil',
                'start' => 'Mars',
                'image' => 'Marketing.jpeg',
                'skills' => ['Reseaux sociaux', 'Canva', 'Redaction'],
                'tagline' => 'Offre fortement consultee ce mois-ci.',
            ],
            [
                'badge' => 'CD',
                'title' => 'Stage Developpeur PHP / MVC',
                'company' => 'Cesi Digital - Teletravail',
                'duration' => '5 mois',
                'salary' => '900 EUR/mois',
                'start' => 'Mai',
                'image' => 'devphp.jpeg',
                'skills' => ['PHP objet', 'MVC', 'MySQL'],
                'tagline' => 'Mission adaptee aux projets web academiques.',
            ],
        ];

        $studentReviews = [
            [
                'initials' => 'LM',
                'name' => 'Lea Martin',
                'role' => 'Etudiante 3e annee - Stage chez Tech Horizon',
                'rating' => 5,
                'text' => 'J ai trouve mon stage rapidement et j ai surtout pu suivre mes candidatures sans me disperser entre plusieurs outils.',
                'date' => 'Avis poste le 14 mars',
            ],
            [
                'initials' => 'NK',
                'name' => 'Nina Kouassi',
                'role' => 'Etudiante 2e annee - Stage chez Nova Media',
                'rating' => 4,
                'text' => 'Les offres etaient faciles a comparer et j ai bien aime avoir les informations principales directement visibles sur chaque fiche.',
                'date' => 'Avis poste le 11 mars',
            ],
            [
                'initials' => 'SB',
                'name' => 'Sami Benali',
                'role' => 'Etudiant 3e annee - Stage chez Cesi Digital',
                'rating' => 5,
                'text' => 'Le site m a aide a centraliser mes recherches et a garder une vue claire sur les entreprises auxquelles j avais deja postule.',
                'date' => 'Avis poste le 8 mars',
            ],
        ];

        View::render('home', [
            'title' => 'Web4Stage - Portail de stages',
            'popularOffers' => $popularOffers,
            'studentReviews' => $studentReviews,
        ]);
    }
}
