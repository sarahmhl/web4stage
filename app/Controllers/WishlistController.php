<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Offer;
use App\Models\Wishlist;
use Core\Auth;
use Core\Security;
use Core\View;

class WishlistController extends BaseController
{
    public function index(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        $items = [];
        try {
            $items = Wishlist::listForStudent((int) ($this->currentUser()['id'] ?? 0));
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de charger votre wish-list pour le moment.');
        }

        $pagination = $this->paginateArray($items, 6);

        View::render('wishlist/index', [
            'title' => 'Web4Stage - Ma wish-list',
            'items' => $pagination['items'],
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'totalItems' => $pagination['totalItems'],
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function toggle(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect($this->redirectTarget((string) ($_POST['redirect_to'] ?? 'wishlist')));
        }

        $studentId = (int) ($this->currentUser()['id'] ?? 0);
        $offerId = (int) ($_POST['offer_id'] ?? 0);

        if ($studentId <= 0 || $offerId <= 0) {
            $this->flash('error', 'Offre introuvable.');
            $this->redirect($this->redirectTarget((string) ($_POST['redirect_to'] ?? 'wishlist')));
        }

        try {
            $offer = Offer::findDetail($offerId);
            if ($offer === null) {
                $this->flash('error', 'Offre introuvable.');
                $this->redirect($this->redirectTarget((string) ($_POST['redirect_to'] ?? 'wishlist')));
            }

            $result = Wishlist::toggle($studentId, $offerId);
            if (($result['active'] ?? false) === true) {
                $this->flash('success-wishlist', 'Offre ajoutée à votre wish-list.');
            } else {
                $this->flash('success-wishlist-remove', 'Offre retirée de votre wish-list.');
            }
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de mettre à jour la wish-list pour le moment.');
        }

        $this->redirect($this->redirectTarget((string) ($_POST['redirect_to'] ?? 'wishlist'), $offerId));
    }

    private function redirectTarget(string $redirectTo, int $offerId = 0): string
    {
        $redirectTo = trim($redirectTo);
        if ($redirectTo === '' || $redirectTo === 'wishlist') {
            return '/wishlist';
        }

        if (str_starts_with($redirectTo, 'offres/detail')) {
            return '/' . ltrim($redirectTo, '/');
        }

        if ($offerId > 0) {
            return '/offres/detail?id=' . $offerId;
        }

        return '/wishlist';
    }
}
