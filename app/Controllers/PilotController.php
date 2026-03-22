<?php

declare(strict_types=1);

// Ce controleur gere l espace pilote : tableau de bord, suivi promo, avis et creation d offres.

namespace App\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Offer;
use App\Models\StudentFeedback;
use App\Models\User;
use Core\Auth;
use Core\Security;
use Core\View;

class PilotController extends BaseController
{
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $students = [];
        $applications = [];
        $companies = [];
        $feedbacks = [];
        $stats = [
            'students' => 0,
            'applications' => 0,
            'interviews' => 0,
            'companies' => 0,
            'feedbacks' => 0,
        ];

        try {
            $students = Application::studentsToFollowUp();
            $applications = Application::listForPilot();
            $companies = Application::companiesToFollowUp();
            $feedbacks = StudentFeedback::all();

            $stats = [
                'students' => count(User::all(Auth::ROLE_ETUDIANT)),
                'applications' => count($applications),
                'interviews' => count(array_filter(
                    $applications,
                    static fn (array $application): bool => (string) ($application['statut'] ?? '') === 'ENTRETIEN'
                )),
                'companies' => count(Company::allWithStats()),
                'feedbacks' => count($feedbacks),
            ];
        } catch (\Throwable $e) {
            $this->flash('error', 'Certaines données pilote n ont pas pu être chargées.');
        }

        View::render('pilot/dashboard', [
            'title' => 'Web4Stage - Espace pilote',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'stats' => $stats,
            'recentApplications' => array_slice($applications, 0, 4),
            'studentsToFollowUp' => array_slice($students, 0, 4),
            'companiesToFollowUp' => array_slice($companies, 0, 4),
            'latestFeedbacks' => array_slice($feedbacks, 0, 4),
        ]);
    }

    public function createOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $oldInput = $_SESSION['pilot_offer_old'] ?? null;
        unset($_SESSION['pilot_offer_old']);

        View::render('pilot/offers-create', [
            'title' => 'Web4Stage - Ajouter une offre',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'companies' => Offer::companyOptions(),
            'imageOptions' => $this->imageOptions(),
            'csrfToken' => Security::generateCsrfToken(),
            'error' => null,
            'success' => null,
            'old' => is_array($oldInput) ? $oldInput : [],
        ]);
    }

    public function storeOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/offres/ajouter');
        }

        $data = $this->sanitizeOfferInput($_POST);
        $_SESSION['pilot_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect('/pilote/offres/ajouter');
        }

        try {
            Offer::create($data);
            unset($_SESSION['pilot_offer_old']);
            $this->flash('success', 'L offre de stage a bien été ajoutée.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d enregistrer l offre pour le moment.');
        }

        $this->redirect('/pilote/offres/ajouter');
    }

    public function reviews(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $feedbacks = [];
        try {
            $feedbacks = StudentFeedback::all();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les avis étudiants.');
        }

        View::render('pilot/reviews', [
            'title' => 'Web4Stage - Retours étudiants',
            'feedbacks' => $feedbacks,
        ]);
    }

    public function followUps(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $students = [];
        $applications = [];
        try {
            $students = Application::studentsToFollowUp();
            $applications = Application::listForPilot();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger le suivi des étudiants.');
        }

        View::render('pilot/follow-up', [
            'title' => 'Web4Stage - Relances et suivi',
            'students' => $students,
            'applications' => $applications,
        ]);
    }

    public function companies(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $companies = [];
        try {
            $companies = Company::allWithStats();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les entreprises.');
        }

        View::render('pilot/companies', [
            'title' => 'Web4Stage - Entreprises partenaires',
            'companies' => $companies,
        ]);
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function sanitizeOfferInput(array $input): array
    {
        $salary = trim((string) ($input['base_remuneration'] ?? ''));
        $duration = trim((string) ($input['duree_mois'] ?? ''));

        return [
            'id_entreprise' => (int) ($input['id_entreprise'] ?? 0),
            'titre' => trim((string) ($input['titre'] ?? '')),
            'description' => trim((string) ($input['description'] ?? '')),
            'base_remuneration' => $salary === '' ? null : (float) str_replace(',', '.', $salary),
            'date_offre' => trim((string) ($input['date_offre'] ?? '')),
            'duree_mois' => $duration === '' ? null : (int) $duration,
            'image_path' => trim((string) ($input['image_path'] ?? '')),
            'skills' => trim((string) ($input['skills'] ?? '')),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateOfferInput(array $data): ?string
    {
        if ((int) $data['id_entreprise'] <= 0) {
            return 'Merci de sélectionner une entreprise.';
        }

        if ((string) $data['titre'] === '') {
            return 'Merci de saisir un titre pour l offre.';
        }

        if ((string) $data['description'] === '') {
            return 'Merci de renseigner une description.';
        }

        if ((string) $data['date_offre'] === '') {
            return 'Merci de renseigner la date de publication.';
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $data['date_offre'])) {
            return 'La date de publication doit être au format AAAA-MM-JJ.';
        }

        if ($data['duree_mois'] !== null && (int) $data['duree_mois'] <= 0) {
            return 'La durée doit être supérieure à zéro.';
        }

        return null;
    }

    /**
     * @return array<int, array{file:string,label:string}>
     */
    private function imageOptions(): array
    {
        return [
            ['file' => 'devfontend.jpeg', 'label' => 'Développement front-end'],
            ['file' => 'devphp.jpeg', 'label' => 'Développement PHP / back-end'],
            ['file' => 'devweb.jpeg', 'label' => 'Développement web général'],
            ['file' => 'design.jpg', 'label' => 'Design / UX / UI'],
            ['file' => 'Marketing.jpeg', 'label' => 'Marketing / communication'],
            ['file' => 'default.svg', 'label' => 'Illustration neutre'],
        ];
    }
}
