<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Models\Application;
use App\Models\Offer;
use App\Models\StudentDocument;
use Core\Auth;
use Core\Security;
use Core\View;

class ApplicationController extends BaseController
{
    public function index(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $applications = [];
        try {
            $applications = Application::listForStudent((int) ($this->currentUser()['id'] ?? 0));
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger vos candidatures pour le moment.');
        }

        $pagination = $this->paginateArray($applications, 5);

        View::render('applications/index', [
            'title' => 'Web4Stage - Mes candidatures',
            'applications' => $pagination['items'],
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalApplications' => $pagination['totalItems'],
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $offerId = (int) ($_GET['id'] ?? 0);
        if ($offerId <= 0) {
            $this->renderNotFound();
            return;
        }

        try {
            $offer = Offer::findDetail($offerId);
            $documents = StudentDocument::findByStudent((int) ($this->currentUser()['id'] ?? 0));
        } catch (\Throwable $e) {
            $offer = null;
            $documents = null;
        }

        if ($offer === null) {
            $this->renderNotFound();
            return;
        }

        View::render('applications/create', [
            'title' => 'Web4Stage - Postuler',
            'offer' => $offer,
            'documents' => $documents,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect('/candidatures');
        }

        $offerId = (int) ($_POST['offer_id'] ?? 0);
        $cvPath = trim((string) ($_POST['existing_cv_path'] ?? ''));
        $letter = trim((string) ($_POST['lettre_motivation'] ?? ''));
        $comment = trim((string) ($_POST['commentaire'] ?? ''));

        try {
            $uploadedCvPath = $this->storeUploadedFile('cv_file', 'uploads/cv', ['pdf', 'doc', 'docx']);
            if ($uploadedCvPath !== null) {
                $cvPath = $uploadedCvPath;

                $existingDocuments = StudentDocument::findByStudent((int) ($this->currentUser()['id'] ?? 0));
                StudentDocument::save(
                    (int) ($this->currentUser()['id'] ?? 0),
                    (string) ($existingDocuments['lettre_type'] ?? ''),
                    $cvPath
                );
            }
        } catch (\Throwable $e) {
            $this->flash('error', $e->getMessage());
            $this->redirect('/candidatures/nouvelle?id=' . $offerId);
        }

        if ($offerId <= 0 || $cvPath === '' || $letter === '') {
            $this->flash('error', 'Merci de fournir votre CV et une lettre de motivation.');
            $this->redirect('/candidatures/nouvelle?id=' . $offerId);
        }

        try {
            Application::create([
                'id_offre' => $offerId,
                'id_etudiant' => (int) ($this->currentUser()['id'] ?? 0),
                'commentaire' => $comment,
                'lettre_motivation' => $letter,
                'cv_path' => $cvPath,
            ]);
            $this->flash('success', 'Votre candidature a bien été envoyée.');
            $this->redirect('/candidatures');
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible d envoyer la candidature. Vous avez peut-être déjà postulé à cette offre.');
            $this->redirect('/candidatures/nouvelle?id=' . $offerId);
        }
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Web4Stage - Offre introuvable',
        ]);
    }
}

