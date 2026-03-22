<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Offer;
use App\Models\Wishlist;
use Core\Auth;
use Core\Security;
use Core\View;

class OfferController extends BaseController
{
    public function index(): void
    {
        $filters = [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'city' => trim((string) ($_GET['city'] ?? '')),
            'skill' => trim((string) ($_GET['skill'] ?? '')),
            'duration' => trim((string) ($_GET['duration'] ?? '')),
        ];

        $perPage = 3;
        $requestedPage = (int) ($_GET['page'] ?? 1);
        $currentPage = max(1, $requestedPage);
        $offset = max(0, ($currentPage - 1) * $perPage);

        try {
            $totalOffers = Offer::countMatching($filters);
            $totalPages = max(1, (int) ceil($totalOffers / $perPage));
            $currentPage = min($currentPage, $totalPages);
            $offset = max(0, ($currentPage - 1) * $perPage);
            $offers = Offer::search($filters, $perPage, $offset);
            $skillOptions = Offer::allSkills();
        } catch (\Throwable $e) {
            $offers = [];
            $skillOptions = [];
            $totalOffers = 0;
            $totalPages = 1;
            $this->flash('error', 'Impossible de charger les offres pour le moment.');
        }

        $wishlistIds = [];
        if (Auth::checkRole(Auth::ROLE_ETUDIANT)) {
            try {
                $wishlistIds = Wishlist::offerIdsForStudent((int) (Auth::user()['id'] ?? 0));
            } catch (\Throwable $e) {
                $wishlistIds = [];
            }
        }

        View::render('offers/index', [
            'title' => 'Web4Stage - Offres de stage',
            'offers' => $offers,
            'filters' => $filters,
            'skillOptions' => $skillOptions,
            'wishlistIds' => $wishlistIds,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalOffers' => $totalOffers,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function show(): void
    {
        $offerId = (int) ($_GET['id'] ?? 0);

        if ($offerId <= 0) {
            $this->renderNotFound();
            return;
        }

        try {
            $offer = Offer::findDetail($offerId);
            if ($offer === null) {
                $this->renderNotFound();
                return;
            }

            $relatedOffers = Offer::findByCompany((int) $offer['company_id'], $offerId, 3);
        } catch (\Throwable $e) {
            $this->renderNotFound();
            return;
        }

        $studentId = (int) (Auth::user()['id'] ?? 0);
        $isWishlisted = false;
        if (Auth::checkRole(Auth::ROLE_ETUDIANT) && $studentId > 0) {
            try {
                $isWishlisted = Wishlist::has($studentId, $offerId);
            } catch (\Throwable $e) {
                $isWishlisted = false;
            }
        }

        View::render('offers/show', [
            'title' => 'Web4Stage - ' . (string) $offer['title'],
            'offer' => $offer,
            'relatedOffers' => $relatedOffers,
            'isWishlisted' => $isWishlisted,
            'canApply' => Auth::checkRole(Auth::ROLE_ETUDIANT),
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Web4Stage - Offre introuvable',
        ]);
    }
}
