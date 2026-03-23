<?php

declare(strict_types=1);

// Ce controleur gere les ecrans admin : dashboard, comptes, moderation, qualite et edition des offres.

namespace App\Controllers;

use App\Models\Company;
use App\Models\CompanyReview;
use App\Models\Offer;
use App\Models\StudentFeedback;
use App\Models\User;
use Core\Auth;
use Core\Security;
use Core\View;

class AdminController extends BaseController
{
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $users = [];
        $offers = [];
        $companies = [];
        $feedbacks = [];
        $companyReviews = [];
        $stats = [
            'users' => 0,
            'offers' => 0,
            'companies' => 0,
            'pendingActions' => 0,
        ];

        try {
            $users = User::all();
            $offers = Offer::allForManagement();
            $companies = Company::allWithStats();
            $feedbacks = StudentFeedback::all();
            $companyReviews = CompanyReview::all();

            $stats = [
                'users' => count($users),
                'offers' => count($offers),
                'companies' => count($companies),
                'pendingActions' => count($feedbacks) + count($companyReviews),
            ];
        } catch (\Throwable $e) {
            $this->flash('error', 'Certaines données d administration n ont pas pu être chargées.');
        }

        View::render('admin/dashboard', [
            'title' => 'Web4Stage - Administration',
            'adminName' => Auth::user()['prenom'] ?? 'Administrateur',
            'stats' => $stats,
            'users' => array_slice($users, 0, 5),
            'offers' => array_slice($offers, 0, 5),
            'feedbacks' => array_slice($feedbacks, 0, 4),
            'companyReviews' => array_slice($companyReviews, 0, 4),
        ]);
    }

    public function editOffers(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offers = Offer::allForManagement();
        $selectedOfferId = max(0, (int) ($_GET['id'] ?? 0));

        if ($selectedOfferId === 0 && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
        }

        $selectedOffer = $selectedOfferId > 0 ? Offer::findForEdit($selectedOfferId) : null;
        if ($selectedOffer === null && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
            $selectedOffer = $selectedOfferId > 0 ? Offer::findForEdit($selectedOfferId) : null;
        }

        $oldInput = $_SESSION['admin_offer_old'] ?? null;
        unset($_SESSION['admin_offer_old']);

        if (is_array($oldInput) && (int) ($oldInput['id_offre'] ?? 0) === $selectedOfferId && $selectedOffer !== null) {
            $selectedOffer = array_merge($selectedOffer, $oldInput);
        }

        View::render('admin/offers-edit', [
            'title' => 'Web4Stage - Modifier les offres',
            'adminName' => Auth::user()['prenom'] ?? 'Administrateur',
            'offers' => $offers,
            'selectedOffer' => $selectedOffer,
            'selectedOfferId' => $selectedOfferId,
            'companies' => Offer::companyOptions(),
            'imageOptions' => $this->imageOptions(),
            'csrfToken' => Security::generateCsrfToken(),
            'error' => null,
            'success' => null,
        ]);
    }

    public function updateOffer(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offerId = (int) ($_POST['id_offre'] ?? 0);
        if ($offerId <= 0) {
            $this->flash('error', 'Offre introuvable.');
            $this->redirect('/admin/offres/modifier');
        }

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/admin/offres/modifier?id=' . $offerId);
        }

        $data = $this->sanitizeOfferInput($_POST);
        $data['id_offre'] = $offerId;
        $_SESSION['admin_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect('/admin/offres/modifier?id=' . $offerId);
        }

        try {
            Offer::update($offerId, $data);
            unset($_SESSION['admin_offer_old']);
            $this->flash('success', 'Les modifications de l offre ont bien été enregistrées.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de modifier cette offre pour le moment.');
        }

        $this->redirect('/admin/offres/modifier?id=' . $offerId);
    }

    public function accounts(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $users = [];
        try {
            $users = User::all();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger la liste des comptes.');
        }

        View::render('admin/accounts', [
            'title' => 'Web4Stage - Gestion des comptes',
            'users' => $users,
        ]);
    }

    public function moderation(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $feedbacks = [];
        $companyReviews = [];
        try {
            $feedbacks = StudentFeedback::all();
            $companyReviews = CompanyReview::all();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les retours utilisateurs.');
        }

        View::render('admin/moderation', [
            'title' => 'Web4Stage - Modération',
            'feedbacks' => $feedbacks,
            'companyReviews' => $companyReviews,
        ]);
    }

    public function quality(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offers = [];
        $companies = [];
        try {
            $offers = Offer::allForManagement();
            $companies = Company::allWithStats();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les indicateurs de qualité.');
        }

        View::render('admin/quality', [
            'title' => 'Web4Stage - Qualité globale',
            'offers' => $offers,
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

