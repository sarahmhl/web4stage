<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyReview;
use App\Models\StudentDocument;
use App\Models\StudentFeedback;
use App\Models\Wishlist;
use Core\Auth;
use Core\Security;
use Core\View;

class StudentController extends BaseController
{
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $user = $this->currentUser();
        $studentId = (int) ($user['id'] ?? 0);

        $stats = [
            'wishlist' => 0,
            'applications' => 0,
            'interviews' => 0,
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];
        $wishlistOffers = [];
        $applications = [];
        $documents = null;

        try {
            $stats = array_merge(
                ['wishlist' => Wishlist::countForStudent($studentId)],
                Application::statusCountsForStudent($studentId)
            );
            $wishlistOffers = array_slice(Wishlist::listForStudent($studentId), 0, 2);
            $applications = array_slice(Application::listForStudent($studentId), 0, 4);
            $documents = StudentDocument::findByStudent($studentId);
        } catch (\Throwable $e) {
            $this->flash('error', 'Certaines informations du tableau de bord n ont pas pu être chargées.');
        }

        View::render('student/dashboard', [
            'title' => 'Web4Stage - Espace étudiant',
            'studentName' => $user['prenom'] ?? 'Étudiant',
            'stats' => $stats,
            'wishlistOffers' => $wishlistOffers,
            'applications' => $applications,
            'documents' => $documents,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function feedback(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $feedbacks = [];
        try {
            $feedbacks = StudentFeedback::all();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les avis étudiants pour le moment.');
        }

        View::render('student/feedback', [
            'title' => 'Web4Stage - Avis étudiants',
            'studentName' => $this->currentUser()['prenom'] ?? 'Étudiant',
            'feedbacks' => $feedbacks,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function storeFeedback(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect('/etudiant/avis');
        }

        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = trim((string) ($_POST['comment'] ?? ''));
        if ($rating < 1 || $rating > 5 || $comment === '') {
            $this->flash('error', 'Merci de renseigner une note et un commentaire.');
            $this->redirect('/etudiant/avis');
        }

        try {
            StudentFeedback::create((int) ($this->currentUser()['id'] ?? 0), $rating, $comment);
            $this->flash('success', 'Votre avis étudiant a bien été enregistré.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d enregistrer votre avis pour le moment.');
        }

        $this->redirect('/etudiant/avis');
    }

    public function companyReview(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $selectedCompanyId = (int) ($_GET['id'] ?? 0);

        $companies = [];
        $selectedCompany = null;
        try {
            $companies = Company::allWithStats();
            if ($selectedCompanyId > 0) {
                $selectedCompany = Company::findDetail($selectedCompanyId);
            }
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les entreprises pour le moment.');
        }

        View::render('student/company-review', [
            'title' => 'Web4Stage - Évaluer une entreprise',
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
            'selectedCompanyId' => $selectedCompanyId,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function storeCompanyReview(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect('/etudiant/entreprises/evaluer');
        }

        $companyId = (int) ($_POST['company_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = trim((string) ($_POST['comment'] ?? ''));

        if ($companyId <= 0 || $rating < 1 || $rating > 5 || $comment === '') {
            $this->flash('error', 'Merci de sélectionner une entreprise, une note et un commentaire.');
            $this->redirect('/etudiant/entreprises/evaluer' . ($companyId > 0 ? '?id=' . $companyId : ''));
        }

        try {
            CompanyReview::create((int) ($this->currentUser()['id'] ?? 0), $companyId, $rating, $comment);
            $this->flash('success', 'Votre évaluation d entreprise a bien été enregistrée.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d enregistrer cette évaluation pour le moment.');
        }

        $this->redirect('/etudiant/entreprises/evaluer?id=' . $companyId);
    }

    public function documents(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $documents = null;
        try {
            $documents = StudentDocument::findByStudent((int) ($this->currentUser()['id'] ?? 0));
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger vos documents pour le moment.');
        }

        View::render('student/documents', [
            'title' => 'Web4Stage - Mes documents',
            'documents' => $documents,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function saveDocuments(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect('/etudiant/documents');
        }

        $cvPath = trim((string) ($_POST['existing_cv_path'] ?? ''));
        $letterTemplate = trim((string) ($_POST['letter_template'] ?? ''));

        try {
            $uploadedCvPath = $this->storeUploadedFile('cv_file', 'uploads/cv', ['pdf', 'doc', 'docx']);
            if ($uploadedCvPath !== null) {
                $cvPath = $uploadedCvPath;
            }
        } catch (\Throwable $e) {
            $this->flash('error', $e->getMessage());
            $this->redirect('/etudiant/documents');
        }

        if ($cvPath === '' && $letterTemplate === '') {
            $this->flash('error', 'Merci de renseigner au moins un CV ou une lettre type.');
            $this->redirect('/etudiant/documents');
        }

        try {
            StudentDocument::save((int) ($this->currentUser()['id'] ?? 0), $letterTemplate, $cvPath === '' ? null : $cvPath);
            $this->flash('success', 'Vos documents ont bien été enregistrés.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d enregistrer vos documents pour le moment.');
        }

        $this->redirect('/etudiant/documents');
    }
}

