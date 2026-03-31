<?php
// Regroupe les actions de suivi et de gestion reservees au pilote.

declare(strict_types=1);

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
        $attentionStats = [
            'studentsWithoutApplications' => 0,
            'studentsWithPending' => 0,
            'studentsWithInterviews' => 0,
            'companiesWithoutApplications' => 0,
            'companiesWithOffers' => 0,
            'averageFeedback' => null,
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

            $attentionStats = [
                'studentsWithoutApplications' => count(array_filter($students, static fn (array $student): bool => (int) ($student['applications_count'] ?? 0) === 0)),
                'studentsWithPending' => count(array_filter($students, static fn (array $student): bool => (int) ($student['pending_count'] ?? 0) > 0)),
                'studentsWithInterviews' => count(array_filter($students, static fn (array $student): bool => (int) ($student['interviews_count'] ?? 0) > 0)),
                'companiesWithoutApplications' => count(array_filter($companies, static fn (array $company): bool => (int) ($company['applications_count'] ?? 0) === 0)),
                'companiesWithOffers' => count(array_filter($companies, static fn (array $company): bool => (int) ($company['offers_count'] ?? 0) > 0)),
                'averageFeedback' => $feedbacks !== []
                    ? round(array_sum(array_map(static fn (array $feedback): float => (float) ($feedback['note'] ?? 0), $feedbacks)) / count($feedbacks), 1)
                    : null,
            ];
        } catch (\Throwable $e) {
            $this->flash('error', 'Certaines données de l’espace pilote n’ont pas pu être chargées.');
        }

        View::render('pilot/dashboard', [
            'title' => 'Web4Stage - Espace pilote',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'stats' => $stats,
            'attentionStats' => $attentionStats,
            'recentApplications' => array_slice($applications, 0, 6),
            'studentsToFollowUp' => array_slice($students, 0, 6),
            'companiesToFollowUp' => array_slice($companies, 0, 6),
            'latestFeedbacks' => array_slice($feedbacks, 0, 6),
        ]);
    }

    public function offers(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

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

        $oldInput = $_SESSION['pilot_offer_old'] ?? null;
        unset($_SESSION['pilot_offer_old']);
        if (is_array($oldInput)) {
            $selectedOffer = array_merge($selectedOffer ?? [], $oldInput);
            $selectedOfferId = (int) ($selectedOffer['id_offre'] ?? 0);
            $isNewOffer = (bool) ($oldInput['is_new'] ?? $isNewOffer);
        }

        View::render('pilot/offers-manage', [
            'title' => 'Web4Stage - Gestion des offres - pilote',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'offers' => $offers,
            'selectedOffer' => $selectedOffer,
            'selectedOfferId' => $selectedOfferId,
            'isNewOffer' => $isNewOffer,
            'companies' => Offer::companyOptions(),
            'imageOptions' => $this->imageOptions(),
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function createOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);
        $this->redirect('/pilote/offres?new=1');
    }

    public function storeOffer(): void
    {
        $this->saveOffer();
    }

    public function saveOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $offerId = (int) ($_POST['id_offre'] ?? 0);
        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect($this->offerRedirectPath(['id_offre' => $offerId]));
        }

        $data = $this->sanitizeOfferInput($_POST);
        $data['id_offre'] = $offerId;
        $data['is_new'] = $offerId <= 0;
        $_SESSION['pilot_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->offerRedirectPath($data));
        }

        try {
            if ($offerId > 0) {
                Offer::update($offerId, $data);
                $this->flash('success', 'Les modifications de l’offre ont bien été enregistrées.');
                unset($_SESSION['pilot_offer_old']);
                $this->redirect('/pilote/offres?id=' . $offerId);
            }

            $newOfferId = Offer::create($data);
            $this->flash('success', 'La nouvelle offre a bien été créée.');
            unset($_SESSION['pilot_offer_old']);
            $this->redirect('/pilote/offres?id=' . $newOfferId);
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
        Auth::requireRole(Auth::ROLE_PILOTE);

        $offerId = (int) ($_POST['id_offre'] ?? 0);
        if ($offerId <= 0) {
            $this->flash('error', 'Offre introuvable.');
            $this->redirect('/pilote/offres');
        }

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/offres?id=' . $offerId);
        }

        try {
            Offer::delete($offerId);
            unset($_SESSION['pilot_offer_old']);
            $this->flash('success', 'L’offre a bien été supprimée.');
            $this->redirect('/pilote/offres');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cette offre pour le moment.');
            $this->redirect('/pilote/offres?id=' . $offerId);
        }
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

        $pagination = $this->paginateArray($feedbacks, 8);

        View::render('pilot/reviews', [
            'title' => 'Web4Stage - Retours étudiants',
            'feedbacks' => $pagination['items'],
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
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

        $studentsPagination = $this->paginateArray($students, 8, 'students_page');
        $applicationsPagination = $this->paginateArray($applications, 6, 'applications_page');

        View::render('pilot/follow-up', [
            'title' => 'Web4Stage - Relances et suivi',
            'students' => $studentsPagination['items'],
            'applications' => $applicationsPagination['items'],
            'studentsCurrentPage' => $studentsPagination['currentPage'],
            'studentsTotalPages' => $studentsPagination['totalPages'],
            'studentsTotalItems' => $studentsPagination['totalItems'],
            'applicationsCurrentPage' => $applicationsPagination['currentPage'],
            'applicationsTotalPages' => $applicationsPagination['totalPages'],
            'applicationsTotalItems' => $applicationsPagination['totalItems'],
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

        if (!$isNewCompany && $selectedCompany === null && $companies !== []) {
            $selectedCompanyId = (int) ($companies[0]['id_entreprise'] ?? 0);
            $selectedCompany = $selectedCompanyId > 0 ? Company::findById($selectedCompanyId) : null;
        }

        $oldInput = $_SESSION['pilot_company_old'] ?? null;
        unset($_SESSION['pilot_company_old']);
        if (is_array($oldInput)) {
            $selectedCompany = array_merge($selectedCompany ?? [], $oldInput);
            $selectedCompanyId = (int) ($selectedCompany['id_entreprise'] ?? 0);
            $isNewCompany = (bool) ($oldInput['is_new'] ?? $isNewCompany);
        }

        $pagination = $this->paginateArray($companies, 8);

        View::render('pilot/companies-manage', [
            'title' => 'Web4Stage - Gestion des entreprises - pilote',
            'companies' => $pagination['items'],
            'selectedCompany' => $selectedCompany,
            'selectedCompanyId' => $selectedCompanyId,
            'isNewCompany' => $isNewCompany,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalCompanies' => $pagination['totalItems'],
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function saveCompany(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/entreprises');
        }

        $data = $this->sanitizeCompanyInput($_POST);
        $_SESSION['pilot_company_old'] = $data;

        $error = $this->validateCompanyInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->companyRedirectPath($data));
        }

        try {
            if ((int) $data['id_entreprise'] > 0) {
                Company::update((int) $data['id_entreprise'], $data);
                $this->flash('success', 'La fiche entreprise a bien été mise à jour.');
                unset($_SESSION['pilot_company_old']);
                $this->redirect('/pilote/entreprises?id=' . (int) $data['id_entreprise']);
            }

            $newId = Company::create($data);
            $this->flash('success', 'La nouvelle entreprise a bien été ajoutée.');
            unset($_SESSION['pilot_company_old']);
            $this->redirect('/pilote/entreprises?id=' . $newId);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d’enregistrer cette entreprise pour le moment.');
            $this->redirect($this->companyRedirectPath($data));
        }
    }

    public function deleteCompany(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/entreprises');
        }

        $companyId = (int) ($_POST['id_entreprise'] ?? 0);
        if ($companyId <= 0) {
            $this->flash('error', 'Entreprise introuvable.');
            $this->redirect('/pilote/entreprises');
        }

        try {
            Company::delete($companyId);
            $this->flash('success', 'L’entreprise a bien été supprimée.');
            $this->redirect('/pilote/entreprises');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cette entreprise tant que des offres lui sont rattachées.');
            $this->redirect('/pilote/entreprises?id=' . $companyId);
        }
    }

    public function students(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $students = [];
        try {
            $students = User::all(Auth::ROLE_ETUDIANT);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger la liste des étudiants.');
        }

        $selectedStudentId = max(0, (int) ($_GET['id'] ?? 0));
        $isNewStudent = isset($_GET['new']);

        if (!$isNewStudent && $selectedStudentId === 0 && $students !== []) {
            $selectedStudentId = (int) ($students[0]['id_utilisateur'] ?? 0);
        }

        $selectedStudent = (!$isNewStudent && $selectedStudentId > 0) ? User::findById($selectedStudentId) : null;
        if ($selectedStudent !== null && (string) ($selectedStudent['role'] ?? '') !== Auth::ROLE_ETUDIANT) {
            $selectedStudent = null;
            $selectedStudentId = 0;
        }

        if ($isNewStudent) {
            $selectedStudent = [
                'id_utilisateur' => 0,
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'role' => Auth::ROLE_ETUDIANT,
            ];
        }

        if (!$isNewStudent && $selectedStudent === null && $students !== []) {
            $selectedStudentId = (int) ($students[0]['id_utilisateur'] ?? 0);
            $selectedStudent = $selectedStudentId > 0 ? User::findById($selectedStudentId) : null;
        }

        $oldInput = $_SESSION['pilot_student_old'] ?? null;
        unset($_SESSION['pilot_student_old']);
        if (is_array($oldInput)) {
            $selectedStudent = array_merge($selectedStudent ?? [], $oldInput);
            $selectedStudentId = (int) ($selectedStudent['id_utilisateur'] ?? 0);
            $isNewStudent = (bool) ($oldInput['is_new'] ?? $isNewStudent);
        }

        $pagination = $this->paginateArray($students, 8);

        View::render('pilot/students', [
            'title' => 'Web4Stage - Gestion des étudiants - pilote',
            'students' => $pagination['items'],
            'selectedStudent' => $selectedStudent,
            'selectedStudentId' => $selectedStudentId,
            'isNewStudent' => $isNewStudent,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalStudents' => $pagination['totalItems'],
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function saveStudent(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/etudiants');
        }

        $data = $this->sanitizeStudentInput($_POST);
        $_SESSION['pilot_student_old'] = $data;

        $error = $this->validateStudentInput($data);
        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect($this->studentRedirectPath($data));
        }

        if (User::emailExists((string) $data['email'], (int) ($data['id_utilisateur'] ?: 0) ?: null)) {
            $this->flash('error', 'Cette adresse e-mail est déjà utilisée.');
            $this->redirect($this->studentRedirectPath($data));
        }

        $existingUser = (int) $data['id_utilisateur'] > 0 ? User::findById((int) $data['id_utilisateur']) : null;
        if ($existingUser !== null && (string) ($existingUser['role'] ?? '') !== Auth::ROLE_ETUDIANT) {
            $this->flash('error', 'Seuls les comptes étudiants peuvent être gérés ici.');
            $this->redirect('/pilote/etudiants');
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
                    'role' => Auth::ROLE_ETUDIANT,
                    'mot_de_passe' => $passwordHash,
                ]);
                $this->flash('success', 'Le compte étudiant a bien été mis à jour.');
                unset($_SESSION['pilot_student_old']);
                $this->redirect('/pilote/etudiants?id=' . (int) $data['id_utilisateur']);
            }

            $newId = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'role' => Auth::ROLE_ETUDIANT,
                'mot_de_passe' => $passwordHash,
            ]);
            $this->flash('success', 'Le nouveau compte étudiant a bien été créé.');
            unset($_SESSION['pilot_student_old']);
            $this->redirect('/pilote/etudiants?id=' . $newId);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d’enregistrer ce compte pour le moment.');
            $this->redirect($this->studentRedirectPath($data));
        }
    }

    public function deleteStudent(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session invalide. Merci de réessayer.');
            $this->redirect('/pilote/etudiants');
        }

        $userId = (int) ($_POST['id_utilisateur'] ?? 0);
        if ($userId <= 0) {
            $this->flash('error', 'Compte étudiant introuvable.');
            $this->redirect('/pilote/etudiants');
        }

        $user = User::findById($userId);
        if ($user === null || (string) ($user['role'] ?? '') !== Auth::ROLE_ETUDIANT) {
            $this->flash('error', 'Compte étudiant introuvable.');
            $this->redirect('/pilote/etudiants');
        }

        try {
            User::delete($userId);
            $this->flash('success', 'Le compte étudiant a bien été supprimé.');
            $this->redirect('/pilote/etudiants');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de supprimer ce compte pour le moment.');
            $this->redirect('/pilote/etudiants?id=' . $userId);
        }
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

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function sanitizeStudentInput(array $input): array
    {
        return [
            'id_utilisateur' => (int) ($input['id_utilisateur'] ?? 0),
            'nom' => trim((string) ($input['nom'] ?? '')),
            'prenom' => trim((string) ($input['prenom'] ?? '')),
            'email' => mb_strtolower(trim((string) ($input['email'] ?? ''))),
            'mot_de_passe' => trim((string) ($input['mot_de_passe'] ?? '')),
            'is_new' => (bool) ((int) ($input['id_utilisateur'] ?? 0) === 0),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateStudentInput(array $data): ?string
    {
        if ((string) $data['nom'] === '' || (string) $data['prenom'] === '') {
            return 'Merci de renseigner le nom et le prénom.';
        }

        if (!filter_var((string) $data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Merci de renseigner une adresse e-mail valide.';
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

    private function offerRedirectPath(array $data): string
    {
        return (int) ($data['id_offre'] ?? 0) > 0
            ? '/pilote/offres?id=' . (int) $data['id_offre']
            : '/pilote/offres?new=1';
    }

    private function companyRedirectPath(array $data): string
    {
        return (int) ($data['id_entreprise'] ?? 0) > 0
            ? '/pilote/entreprises?id=' . (int) $data['id_entreprise']
            : '/pilote/entreprises?new=1';
    }

    private function studentRedirectPath(array $data): string
    {
        return (int) ($data['id_utilisateur'] ?? 0) > 0
            ? '/pilote/etudiants?id=' . (int) $data['id_utilisateur']
            : '/pilote/etudiants?new=1';
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
