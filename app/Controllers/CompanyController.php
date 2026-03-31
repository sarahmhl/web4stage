<?php
// Gere la consultation publique des entreprises et de leurs fiches.

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
        $filters = [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'city' => trim((string) ($_GET['city'] ?? '')),
            'sector' => trim((string) ($_GET['sector'] ?? '')),
        ];

        $companies = [];
        try {
            $companies = Company::allWithStats($filters);
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger les entreprises pour le moment.');
        }

        $pagination = $this->paginateArray($companies, 6);

        View::render('companies/index', [
            'title' => 'Web4Stage - Entreprises',
            'companies' => $pagination['items'],
            'filters' => $filters,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalCompanies' => $pagination['totalItems'],
            'metaDescription' => 'Consultez les entreprises partenaires, leurs coordonnées et les offres de stage associées sur Web4Stage.',
            'metaKeywords' => 'entreprises, stages, cesi, partenaires, web4stage',
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
            'canReview' => Auth::checkRole(Auth::ROLE_PILOTE) || Auth::checkRole(Auth::ROLE_ADMIN),
            'csrfToken' => Security::generateCsrfToken(),
            'metaDescription' => 'Fiche entreprise ' . (string) $company['nom'] . ' : contact, offres liées et avis étudiants.',
            'metaKeywords' => 'entreprise, fiche entreprise, avis, offres, web4stage',
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

