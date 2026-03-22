<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Company;
use Core\Auth;
use Core\Security;
use Core\View;

class CompanyController extends BaseController
{
    public function index(): void
    {
        $companies = [];
        try {
            $companies = Company::allWithStats();
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les entreprises pour le moment.');
        }

        View::render('companies/index', [
            'title' => 'Web4Stage - Entreprises',
            'companies' => $companies,
        ]);
    }

    public function show(): void
    {
        $companyId = (int) ($_GET['id'] ?? 0);

        if ($companyId <= 0) {
            $this->renderNotFound();
            return;
        }

        try {
            $company = Company::findDetail($companyId);
        } catch (\Throwable $e) {
            $company = null;
        }

        if ($company === null) {
            $this->renderNotFound();
            return;
        }

        View::render('companies/show', [
            'title' => 'Web4Stage - ' . (string) $company['nom'],
            'company' => $company,
            'canReview' => Auth::checkRole(Auth::ROLE_ETUDIANT),
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Web4Stage - Entreprise introuvable',
        ]);
    }
}
