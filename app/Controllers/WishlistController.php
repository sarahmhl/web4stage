<?php

declare(strict_types=1);


namespace App\Controllers;

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

        View::render('wishlist/index', [
            'title' => 'Web4Stage - Ma wish-list',
            'items' => $items,
            'csrfToken' => Security::generateCsrfToken(),
        ]);
    }

    public function toggle(): void
    {
        Auth::requireRole(Auth::ROLE_ETUDIANT);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $this->flash('error', 'Session expirée, merci de recommencer.');
            $this->redirect('/wishlist');
        }

        $offerId = (int) ($_POST['offer_id'] ?? 0);
        $redirectTo = trim((string) ($_POST['redirect_to'] ?? '/wishlist'));
        if ($offerId <= 0) {
            $this->flash('error', 'Offre introuvable pour la wish-list.');
            $this->redirect($redirectTo === '' ? '/wishlist' : $redirectTo);
        }

        try {
            $result = Wishlist::toggle((int) ($this->currentUser()['id'] ?? 0), $offerId);
            $this->flash(
                'success',
                $result['active'] ? 'Offre ajoutée à votre wish-list.' : 'Offre retirée de votre wish-list.'
            );
        } catch (\Throwable $e) {
            $this->flash('error', 'Impossible de modifier votre wish-list pour le moment.');
        }

        $this->redirect($redirectTo === '' ? '/wishlist' : $redirectTo);
    }
}

