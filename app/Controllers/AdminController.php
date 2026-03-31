<?php
// Regroupe les actions reservees a l'administrateur.

declare(strict_types=1);


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
        $insights = [
            'students' => 0,
            'pilots' => 0,
            'admins' => 0,
            'companiesWithoutOffers' => 0,
            'unratedCompanies' => 0,
            'lowRatedCompanies' => 0,
            'studentFeedbackAverage' => null,
            'companyReviewAverage' => null,
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

            $feedbackAverage = $feedbacks !== []
                ? round(array_sum(array_map(static fn (array $item): float => (float) ($item['note'] ?? 0), $feedbacks)) / count($feedbacks), 1)
                : null;
            $companyReviewAverage = $companyReviews !== []
                ? round(array_sum(array_map(static fn (array $item): float => (float) ($item['note'] ?? 0), $companyReviews)) / count($companyReviews), 1)
                : null;

            $insights = [
                'students' => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_ETUDIANT)),
                'pilots' => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_PILOTE)),
                'admins' => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_ADMIN)),
                'companiesWithoutOffers' => count(array_filter($companies, static fn (array $company): bool => (int) ($company['offers_count'] ?? 0) === 0)),
                'unratedCompanies' => count(array_filter($companies, static fn (array $company): bool => ($company['average_rating'] ?? null) === null)),
                'lowRatedCompanies' => count(array_filter($companies, static fn (array $company): bool => ($company['average_rating'] ?? null) !== null && (float) $company['average_rating'] < 3.5)),
                'studentFeedbackAverage' => $feedbackAverage,
                'companyReviewAverage' => $companyReviewAverage,
            ];
        } catch (\Throwable $e) {
            $this->flash('error', 'Certaines données d’administration n’ont pas pu être chargées.');
        }

        View::render('admin/dashboard', [
            'title' => 'Web4Stage - Administration',
            'adminName' => Auth::user()['prenom'] ?? 'Administrateur',
            'stats' => $stats,
            'insights' => $insights,
            'users' => array_slice($users, 0, 6),
            'offers' => array_slice($offers, 0, 6),
            'companies' => array_slice($companies, 0, 6),
            'feedbacks' => array_slice($feedbacks, 0, 5),
            'companyReviews' => array_slice($companyReviews, 0, 5),
            'metaDescription' => 'Tableau de bord administrateur Web4Stage pour gérer les comptes, les offres, les entreprises et la modération.',
            'metaKeywords' => 'administration, back office, comptes, offres, entreprises, web4stage',
        ]);
    }

    public function editOffers(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offers = Offer::allForManagement();
        $selectedOfferId = max(0, (int) ($_GET['id'] ?? 0));
        $isNewOffer = isset($_GET['new']);

        if (!$isNewOffer && $selectedOfferId === 0 && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
        }

        $selectedOffer = (!$isNewOffer && $selectedOfferId > 0) ? Offer::findForEdit($selectedOfferId) : null;
        if ($isNewOffer) {
            $selectedOffer = [
                'id_offre' => 0,
                'id_entreprise' => 0,
                'titre' => '',
                'description' => '',
                'base_remuneration' => '',
                'date_offre' => date('Y-m-d'),
                'duree_mois' => '',
                'image_path' => '',
                'skills' => [],
            ];
        }

        if (!$isNewOffer && $selectedOffer === null && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
            $selectedOffer = $selectedOfferId > 0 ? Offer::findForEdit($selectedOfferId) : null;
        }

        $oldInput = $_SESSION['admin_offer_old'] ?? null;
        unset($_SESSION['admin_offer_old']);

        if (is_array($oldInput)) {
            $selectedOffer = array_merge($selectedOffer ?? [], $oldInput);
            $selectedOfferId = (int) ($selectedOffer['id_offre'] ?? 0);
            $isNewOffer = (bool) ($oldInput['is_new'] ?? $isNewOffer);
        }

        View::render('admin/offers-edit', [
            'title' => 'Web4Stage - Modifier les offres',
            'adminName' => Auth::user()['prenom'] ?? 'Administrateur',
            'offers' => $offers,
            'selectedOffer' => $selectedOffer,
            'selectedOfferId' => $selectedOfferId,
            'isNewOffer' => $isNewOffer,
            'companies' => Offer::companyOptions(),
            'imageOptions' => $this->imageOptions(),
            'csrfToken' => Security::generateCsrfToken(),
            'error' => null,
            'success' => null,
            'metaDescription' => 'Gestion administrateur des offres de stage publiées sur Web4Stage.',
            'metaKeywords' => 'administration offres, modifier offre, supprimer offre, web4stage',
        ]);
    }

    public function updateOffer(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offerId = (int) ($_POST['id_offre'] ?? 0);
        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect($this->offerRedirectPath(['id_offre' => $offerId]));
        }

        $data = $this->sanitizeOfferInput($_POST);
        $data['id_offre'] = $offerId;
        $data['is_new'] = $offerId <= 0;
        $_SESSION['admin_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->offerRedirectPath($data));
        }

        try {
            if ($offerId > 0) {
                Offer::update($offerId, $data);
                $this->flash('success', 'Les modifications de l’offre ont bien été enregistrées.');
                unset($_SESSION['admin_offer_old']);
                $this->redirect('/admin/offres/modifier?id=' . $offerId);
            }

            $newOfferId = Offer::create($data);
            $this->flash('success', 'La nouvelle offre a bien été créée.');
            unset($_SESSION['admin_offer_old']);
            $this->redirect('/admin/offres/modifier?id=' . $newOfferId);
        } catch (\Throwable $e) {
            $this->flash(
                'error',
                $offerId > 0
                    ? 'Impossible de modifier cette offre pour le moment.'
                    : 'Impossible de créer cette offre pour le moment.'
            );
            $this->redirect($this->offerRedirectPath($data));
        }
    }

    public function deleteOffer(): void
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

        try {
            Offer::delete($offerId);
            unset($_SESSION['admin_offer_old']);
            $this->flash('success', 'L’offre a bien été supprimée.');
            $this->redirect('/admin/offres/modifier');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cette offre pour le moment.');
            $this->redirect('/admin/offres/modifier?id=' . $offerId);
        }
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

        $selectedUserId = max(0, (int) ($_GET['id'] ?? 0));
        $isNewAccount = isset($_GET['new']);

        if (!$isNewAccount && $selectedUserId === 0 && $users !== []) {
            $selectedUserId = (int) ($users[0]['id_utilisateur'] ?? 0);
        }

        $selectedUser = (!$isNewAccount && $selectedUserId > 0) ? User::findById($selectedUserId) : null;
        if ($isNewAccount) {
            $selectedUser = [
                'id_utilisateur' => 0,
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'role' => Auth::ROLE_ETUDIANT,
            ];
        }

        $oldInput = $_SESSION['admin_account_old'] ?? null;
        unset($_SESSION['admin_account_old']);
        if (is_array($oldInput)) {
            $selectedUser = array_merge($selectedUser ?? [], $oldInput);
            $selectedUserId = (int) ($selectedUser['id_utilisateur'] ?? 0);
            $isNewAccount = (bool) ($oldInput['is_new'] ?? $isNewAccount);
        }

        $roleCounts = [
            Auth::ROLE_ETUDIANT => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_ETUDIANT)),
            Auth::ROLE_PILOTE => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_PILOTE)),
            Auth::ROLE_ADMIN => count(array_filter($users, static fn (array $user): bool => (string) ($user['role'] ?? '') === Auth::ROLE_ADMIN)),
        ];

        $pagination = $this->paginateArray($users, 8);

        View::render('admin/accounts', [
            'title' => 'Web4Stage - Gestion des comptes',
            'users' => $pagination['items'],
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUserId,
            'isNewAccount' => $isNewAccount,
            'roleOptions' => $this->roleOptions(),
            'roleCounts' => $roleCounts,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalUsers' => $pagination['totalItems'],
            'csrfToken' => Security::generateCsrfToken(),
            'metaDescription' => 'Gestion des comptes et des rôles utilisateurs dans l’administration Web4Stage.',
            'metaKeywords' => 'comptes, utilisateurs, rôles, administration, web4stage',
        ]);
    }

    public function saveAccount(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/admin/comptes');
        }

        $data = $this->sanitizeAccountInput($_POST);
        $_SESSION['admin_account_old'] = $data;

        $error = $this->validateAccountInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->accountRedirectPath($data));
        }

        if (User::emailExists((string) $data['email'], (int) ($data['id_utilisateur'] ?: 0) ?: null)) {
            $this->flash('error', 'Cette adresse e-mail est déjà utilisée.');
            $this->redirect($this->accountRedirectPath($data));
        }

        $currentAdminId = (int) ($this->currentUser()['id'] ?? 0);
        $existingUser = (int) $data['id_utilisateur'] > 0 ? User::findById((int) $data['id_utilisateur']) : null;

        if (
            $existingUser !== null
            && (string) $existingUser['role'] === Auth::ROLE_ADMIN
            && (string) $data['role'] !== Auth::ROLE_ADMIN
            && User::countByRole(Auth::ROLE_ADMIN) <= 1
        ) {
            $this->flash('error', 'Au moins un compte administrateur doit être conservé.');
            $this->redirect($this->accountRedirectPath($data));
        }

        if ($existingUser !== null && (int) $existingUser['id_utilisateur'] === $currentAdminId && (string) $data['role'] !== Auth::ROLE_ADMIN) {
            $this->flash('error', 'Vous ne pouvez pas retirer le rôle admin de votre session active.');
            $this->redirect($this->accountRedirectPath($data));
        }

        try {
            $passwordHash = trim((string) $data['mot_de_passe']) !== ''
                ? password_hash((string) $data['mot_de_passe'], PASSWORD_DEFAULT)
                : null;

            if ((int) $data['id_utilisateur'] > 0) {
                User::update((int) $data['id_utilisateur'], [
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'mot_de_passe' => $passwordHash,
                ]);
                $this->flash('success', 'Le compte utilisateur a bien été mis à jour.');
                unset($_SESSION['admin_account_old']);
                $this->redirect('/admin/comptes?id=' . (int) $data['id_utilisateur']);
            }

            $newId = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'role' => $data['role'],
                'mot_de_passe' => $passwordHash,
            ]);
            $this->flash('success', 'Le nouveau compte a bien été créé.');
            unset($_SESSION['admin_account_old']);
            $this->redirect('/admin/comptes?id=' . $newId);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d’enregistrer ce compte pour le moment.');
            $this->redirect($this->accountRedirectPath($data));
        }
    }

    public function deleteAccount(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/admin/comptes');
        }

        $userId = (int) ($_POST['id_utilisateur'] ?? 0);
        if ($userId <= 0) {
            $this->flash('error', 'Compte introuvable.');
            $this->redirect('/admin/comptes');
        }

        $currentAdminId = (int) ($this->currentUser()['id'] ?? 0);
        if ($userId === $currentAdminId) {
            $this->flash('error', 'Vous ne pouvez pas supprimer le compte actuellement connecté.');
            $this->redirect('/admin/comptes?id=' . $userId);
        }

        $user = User::findById($userId);
        if ($user === null) {
            $this->flash('error', 'Compte introuvable.');
            $this->redirect('/admin/comptes');
        }

        if ((string) $user['role'] === Auth::ROLE_ADMIN && User::countByRole(Auth::ROLE_ADMIN) <= 1) {
            $this->flash('error', 'Le dernier compte administrateur ne peut pas être supprimé.');
            $this->redirect('/admin/comptes?id=' . $userId);
        }

        try {
            User::delete($userId);
            $this->flash('success', 'Le compte a bien été supprimé.');
            $this->redirect('/admin/comptes');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer ce compte pour le moment.');
            $this->redirect('/admin/comptes?id=' . $userId);
        }
    }

    public function companies(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $companies = [];
        try {
            $companies = Company::allWithStats();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger la liste des entreprises.');
        }

        $selectedCompanyId = max(0, (int) ($_GET['id'] ?? 0));
        $isNewCompany = isset($_GET['new']);

        if (!$isNewCompany && $selectedCompanyId === 0 && $companies !== []) {
            $selectedCompanyId = (int) ($companies[0]['id_entreprise'] ?? 0);
        }

        $selectedCompany = (!$isNewCompany && $selectedCompanyId > 0) ? Company::findById($selectedCompanyId) : null;
        if ($isNewCompany) {
            $selectedCompany = [
                'id_entreprise' => 0,
                'nom' => '',
                'description' => '',
                'ville' => '',
                'secteur' => '',
                'site_web' => '',
                'email_contact' => '',
                'telephone_contact' => '',
            ];
        }

        $oldInput = $_SESSION['admin_company_old'] ?? null;
        unset($_SESSION['admin_company_old']);
        if (is_array($oldInput)) {
            $selectedCompany = array_merge($selectedCompany ?? [], $oldInput);
            $selectedCompanyId = (int) ($selectedCompany['id_entreprise'] ?? 0);
            $isNewCompany = (bool) ($oldInput['is_new'] ?? $isNewCompany);
        }

        $pagination = $this->paginateArray($companies, 8);

        View::render('admin/companies', [
            'title' => 'Web4Stage - Gestion des entreprises',
            'companies' => $pagination['items'],
            'selectedCompany' => $selectedCompany,
            'selectedCompanyId' => $selectedCompanyId,
            'isNewCompany' => $isNewCompany,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalCompanies' => $pagination['totalItems'],
            'csrfToken' => Security::generateCsrfToken(),
            'metaDescription' => 'Gestion des entreprises partenaires dans le back-office Web4Stage.',
            'metaKeywords' => 'entreprises, partenaires, administration, web4stage',
        ]);
    }

    public function saveCompany(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/admin/entreprises');
        }

        $data = $this->sanitizeCompanyInput($_POST);
        $_SESSION['admin_company_old'] = $data;

        $error = $this->validateCompanyInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->companyRedirectPath($data));
        }

        try {
            if ((int) $data['id_entreprise'] > 0) {
                Company::update((int) $data['id_entreprise'], $data);
                $this->flash('success', 'La fiche entreprise a bien été mise à jour.');
                unset($_SESSION['admin_company_old']);
                $this->redirect('/admin/entreprises?id=' . (int) $data['id_entreprise']);
            }

            $newId = Company::create($data);
            $this->flash('success', 'La nouvelle entreprise a bien été ajoutée.');
            unset($_SESSION['admin_company_old']);
            $this->redirect('/admin/entreprises?id=' . $newId);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d’enregistrer cette entreprise pour le moment.');
            $this->redirect($this->companyRedirectPath($data));
        }
    }

    public function deleteCompany(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/admin/entreprises');
        }

        $companyId = (int) ($_POST['id_entreprise'] ?? 0);
        if ($companyId <= 0) {
            $this->flash('error', 'Entreprise introuvable.');
            $this->redirect('/admin/entreprises');
        }

        try {
            Company::delete($companyId);
            $this->flash('success', 'L’entreprise a bien été supprimée.');
            $this->redirect('/admin/entreprises');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cette entreprise tant que des offres lui sont rattachées.');
            $this->redirect('/admin/entreprises?id=' . $companyId);
        }
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

        $feedbackPagination = $this->paginateArray($feedbacks, 8, 'feedback_page');
        $companyReviewPagination = $this->paginateArray($companyReviews, 8, 'company_review_page');

        View::render('admin/moderation', [
            'title' => 'Web4Stage - Modération',
            'feedbacks' => $feedbackPagination['items'],
            'companyReviews' => $companyReviewPagination['items'],
            'feedbackCurrentPage' => $feedbackPagination['currentPage'],
            'feedbackTotalPages' => $feedbackPagination['totalPages'],
            'feedbackTotalItems' => $feedbackPagination['totalItems'],
            'companyReviewCurrentPage' => $companyReviewPagination['currentPage'],
            'companyReviewTotalPages' => $companyReviewPagination['totalPages'],
            'companyReviewTotalItems' => $companyReviewPagination['totalItems'],
            'metaDescription' => 'Modération des avis et évaluations utilisateurs sur Web4Stage.',
            'metaKeywords' => 'modération, avis, évaluations, web4stage',
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

        $offersPagination = $this->paginateArray($offers, 8, 'offers_page');
        $companiesPagination = $this->paginateArray($companies, 8, 'companies_page');

        View::render('admin/quality', [
            'title' => 'Web4Stage - Qualité globale',
            'offers' => $offersPagination['items'],
            'companies' => $companiesPagination['items'],
            'offersCurrentPage' => $offersPagination['currentPage'],
            'offersTotalPages' => $offersPagination['totalPages'],
            'offersTotalItems' => $offersPagination['totalItems'],
            'companiesCurrentPage' => $companiesPagination['currentPage'],
            'companiesTotalPages' => $companiesPagination['totalPages'],
            'companiesTotalItems' => $companiesPagination['totalItems'],
            'metaDescription' => 'Vue synthèse de la qualité globale de la plateforme Web4Stage.',
            'metaKeywords' => 'qualité, indicateurs, administration, web4stage',
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
            return 'Merci de saisir un titre pour l’offre.';
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
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function sanitizeAccountInput(array $input): array
    {
        return [
            'id_utilisateur' => (int) ($input['id_utilisateur'] ?? 0),
            'nom' => trim((string) ($input['nom'] ?? '')),
            'prenom' => trim((string) ($input['prenom'] ?? '')),
            'email' => mb_strtolower(trim((string) ($input['email'] ?? ''))),
            'role' => trim((string) ($input['role'] ?? Auth::ROLE_ETUDIANT)),
            'mot_de_passe' => trim((string) ($input['mot_de_passe'] ?? '')),
            'is_new' => (bool) ((int) ($input['id_utilisateur'] ?? 0) === 0),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateAccountInput(array $data): ?string
    {
        if ((string) $data['nom'] === '' || (string) $data['prenom'] === '') {
            return 'Merci de renseigner le nom et le prénom.';
        }

        if (!filter_var((string) $data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Merci de renseigner une adresse e-mail valide.';
        }

        if (!in_array((string) $data['role'], array_keys($this->roleOptions()), true)) {
            return 'Rôle utilisateur invalide.';
        }

        $password = (string) $data['mot_de_passe'];
        if ((int) $data['id_utilisateur'] === 0 && strlen($password) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères pour un nouveau compte.';
        }

        if ((int) $data['id_utilisateur'] > 0 && $password !== '' && strlen($password) < 8) {
            return 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
        }

        return null;
    }

    private function accountRedirectPath(array $data): string
    {
        return (int) ($data['id_utilisateur'] ?? 0) > 0
            ? '/admin/comptes?id=' . (int) $data['id_utilisateur']
            : '/admin/comptes?new=1';
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function sanitizeCompanyInput(array $input): array
    {
        $site = trim((string) ($input['site_web'] ?? ''));
        if ($site !== '' && !preg_match('#^https?://#i', $site)) {
            $site = 'https://' . $site;
        }

        return [
            'id_entreprise' => (int) ($input['id_entreprise'] ?? 0),
            'nom' => trim((string) ($input['nom'] ?? '')),
            'description' => trim((string) ($input['description'] ?? '')),
            'ville' => trim((string) ($input['ville'] ?? '')),
            'secteur' => trim((string) ($input['secteur'] ?? '')),
            'site_web' => $site,
            'email_contact' => mb_strtolower(trim((string) ($input['email_contact'] ?? ''))),
            'telephone_contact' => trim((string) ($input['telephone_contact'] ?? '')),
            'is_new' => (bool) ((int) ($input['id_entreprise'] ?? 0) === 0),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateCompanyInput(array $data): ?string
    {
        if ((string) $data['nom'] === '') {
            return 'Merci de renseigner le nom de l’entreprise.';
        }

        if ((string) $data['email_contact'] !== '' && !filter_var((string) $data['email_contact'], FILTER_VALIDATE_EMAIL)) {
            return 'Merci de renseigner un e-mail de contact valide.';
        }

        if ((string) $data['site_web'] !== '' && !filter_var((string) $data['site_web'], FILTER_VALIDATE_URL)) {
            return 'Merci de renseigner une URL de site web valide.';
        }

        return null;
    }

    private function companyRedirectPath(array $data): string
    {
        return (int) ($data['id_entreprise'] ?? 0) > 0
            ? '/admin/entreprises?id=' . (int) $data['id_entreprise']
            : '/admin/entreprises?new=1';
    }

    private function offerRedirectPath(array $data): string
    {
        return (int) ($data['id_offre'] ?? 0) > 0
            ? '/admin/offres/modifier?id=' . (int) $data['id_offre']
            : '/admin/offres/modifier?new=1';
    }

    /**
     * @return array<string, string>
     */
    private function roleOptions(): array
    {
        return [
            Auth::ROLE_ETUDIANT => 'Étudiant',
            Auth::ROLE_PILOTE => 'Pilote',
            Auth::ROLE_ADMIN => 'Administrateur',
        ];
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

