<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Auth;
use Core\View;

class StudentController
{
    public function dashboard(): void
    {
        // Acces reserve aux etudiants connectes
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        // Donnees d'exemple pour le tableau de bord
        $stats = [
            'wishlist' => 7,
            'applications' => 4,
            'interviews' => 1,
            'pending' => 3,
            'accepted' => 1,
            'rejected' => 0,
        ];

        View::render('student/dashboard', [
            'title' => 'Web4Stage - Espace étudiant',
            'studentName' => Auth::user()['prenom'] ?? 'Étudiant',
            'stats' => $stats,
        ]);
    }
}
