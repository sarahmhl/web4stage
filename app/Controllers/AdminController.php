<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Offer;
use Core\Auth;
use Core\Security;
use Core\View;

class AdminController
{
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $stats = [
            'users' => 126,
            'offers' => 37,
            'companies' => 24,
            'pendingActions' => 5,
        ];

        View::render('admin/dashboard', [
            'title' => 'Web4Stage - Administration',
            'adminName' => Auth::user()['prenom'] ?? 'Administrateur',
            'stats' => $stats,
        ]);
    }

    public function editOffers(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offers = Offer::allForManagement();
        $selectedOfferId = max(1, (int) ($_GET['id'] ?? 0));

        if ($selectedOfferId <= 0 && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
        }

        $selectedOffer = $selectedOfferId > 0 ? Offer::findForEdit($selectedOfferId) : null;
        if ($selectedOffer === null && $offers !== []) {
            $selectedOfferId = (int) ($offers[0]['id_offre'] ?? 0);
            $selectedOffer = $selectedOfferId > 0 ? Offer::findForEdit($selectedOfferId) : null;
        }

        $flashError = $_SESSION['admin_offer_error'] ?? null;
        $flashSuccess = $_SESSION['admin_offer_success'] ?? null;
        $oldInput = $_SESSION['admin_offer_old'] ?? null;
        unset($_SESSION['admin_offer_error'], $_SESSION['admin_offer_success'], $_SESSION['admin_offer_old']);

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
            'error' => is_string($flashError) ? $flashError : null,
            'success' => is_string($flashSuccess) ? $flashSuccess : null,
        ]);
    }

    public function updateOffer(): void
    {
        Auth::requireRole(Auth::ROLE_ADMIN);

        $offerId = (int) ($_POST['id_offre'] ?? 0);
        if ($offerId <= 0) {
            $_SESSION['admin_offer_error'] = 'Offre introuvable.';
            $this->redirect('/admin/offres/modifier');
        }

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $_SESSION['admin_offer_error'] = 'Session invalide. Merci de reessayer.';
            $this->redirect('/admin/offres/modifier?id=' . $offerId);
        }

        $data = $this->sanitizeOfferInput($_POST);
        $data['id_offre'] = $offerId;
        $_SESSION['admin_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $_SESSION['admin_offer_error'] = $error;
            $this->redirect('/admin/offres/modifier?id=' . $offerId);
        }

        try {
            Offer::update($offerId, $data);
            $_SESSION['admin_offer_success'] = 'L offre a bien ete mise a jour.';
            unset($_SESSION['admin_offer_old']);
        } catch (\Throwable $e) {
            $_SESSION['admin_offer_error'] = 'Impossible de modifier cette offre pour le moment.';
        }

        $this->redirect('/admin/offres/modifier?id=' . $offerId);
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
            return 'Merci de selectionner une entreprise.';
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
            return 'La date de publication doit etre au format AAAA-MM-JJ.';
        }

        if ($data['duree_mois'] !== null && (int) $data['duree_mois'] <= 0) {
            return 'La duree doit etre superieure a zero.';
        }

        return null;
    }

    /**
     * @return array<int, array{file:string,label:string}>
     */
    private function imageOptions(): array
    {
        return [
            ['file' => 'devfontend.jpeg', 'label' => 'Developpement front-end'],
            ['file' => 'devphp.jpeg', 'label' => 'Developpement PHP / back-end'],
            ['file' => 'devweb.jpeg', 'label' => 'Developpement web general'],
            ['file' => 'design.jpg', 'label' => 'Design / UX / UI'],
            ['file' => 'Marketing.jpeg', 'label' => 'Marketing / communication'],
            ['file' => 'default.svg', 'label' => 'Illustration neutre'],
        ];
    }

    private function buildUrl(string $path): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $cleanPath = '/' . ltrim($path, '/');

        return str_replace(' ', '%20', rtrim($scriptName, '/') . $cleanPath);
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $this->buildUrl($path));
        exit;
    }
}
