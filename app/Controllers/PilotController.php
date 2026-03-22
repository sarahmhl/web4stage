<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Offer;
use Core\Auth;
use Core\Security;
use Core\View;

class PilotController
{
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $stats = [
            'students' => 42,
            'applications' => 58,
            'interviews' => 11,
            'companies' => 18,
        ];

        View::render('pilot/dashboard', [
            'title' => 'Web4Stage - Espace pilote',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'stats' => $stats,
        ]);
    }

    public function createOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        $flashError = $_SESSION['pilot_offer_error'] ?? null;
        $flashSuccess = $_SESSION['pilot_offer_success'] ?? null;
        $oldInput = $_SESSION['pilot_offer_old'] ?? null;
        unset($_SESSION['pilot_offer_error'], $_SESSION['pilot_offer_success'], $_SESSION['pilot_offer_old']);

        View::render('pilot/offers-create', [
            'title' => 'Web4Stage - Ajouter une offre',
            'pilotName' => Auth::user()['prenom'] ?? 'Pilote',
            'companies' => Offer::companyOptions(),
            'imageOptions' => $this->imageOptions(),
            'csrfToken' => Security::generateCsrfToken(),
            'error' => is_string($flashError) ? $flashError : null,
            'success' => is_string($flashSuccess) ? $flashSuccess : null,
            'old' => is_array($oldInput) ? $oldInput : [],
        ]);
    }

    public function storeOffer(): void
    {
        Auth::requireRole(Auth::ROLE_PILOTE);

        if (!Security::checkCsrfToken((string) ($_POST['_csrf'] ?? ''))) {
            $_SESSION['pilot_offer_error'] = 'Session invalide. Merci de reessayer.';
            $this->redirect('/pilote/offres/ajouter');
        }

        $data = $this->sanitizeOfferInput($_POST);
        $_SESSION['pilot_offer_old'] = $data;

        $error = $this->validateOfferInput($data);
        if ($error !== null) {
            $_SESSION['pilot_offer_error'] = $error;
            $this->redirect('/pilote/offres/ajouter');
        }

        try {
            Offer::create($data);
            $_SESSION['pilot_offer_success'] = 'La nouvelle offre de stage a bien ete ajoutee.';
            unset($_SESSION['pilot_offer_old']);
        } catch (\Throwable $e) {
            $_SESSION['pilot_offer_error'] = 'Impossible d enregistrer l offre pour le moment.';
        }

        $this->redirect('/pilote/offres/ajouter');
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
