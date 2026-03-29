<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Auth;
use Core\Flash;
use Core\Url;

abstract class BaseController
{
    protected function buildUrl(string $path = ''): string
    {
        return Url::route($path);
    }

    protected function redirect(string $path = ''): void
    {
        header('Location: ' . $this->buildUrl($path));
        exit;
    }

    protected function flash(string $type, string $message): void
    {
        Flash::add($type, $message);
    }

    /**
     * @template T
     * @param array<int, T> $items
     * @return array{items:array<int, T>,currentPage:int,totalPages:int,totalItems:int,perPage:int}
     */
    protected function paginateArray(array $items, int $perPage = 6, string $pageParam = 'page'): array
    {
        $totalItems = count($items);
        $perPage = max(1, $perPage);
        $requestedPage = (int) ($_GET[$pageParam] ?? 1);
        $currentPage = max(1, $requestedPage);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'perPage' => $perPage,
        ];
    }

    /**
     * @return array{id:int,nom:string,prenom:string,email:string,role:string}|null
     */
    protected function currentUser(): ?array
    {
        return Auth::user();
    }

    /**
     * Stocke un fichier envoyé dans le dossier public et retourne son chemin relatif.
     *
     * @param array<int, string> $allowedExtensions
     */
    protected function storeUploadedFile(
        string $fieldName,
        string $publicDirectory,
        array $allowedExtensions,
        int $maxBytes = 5242880
    ): ?string {
        $file = $_FILES[$fieldName] ?? null;
        if (!is_array($file)) {
            return null;
        }

        $errorCode = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($errorCode !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->uploadErrorMessage($errorCode));
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException('Le fichier transmis est invalide.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0) {
            throw new \RuntimeException('Le fichier transmis est vide.');
        }

        if ($size > $maxBytes) {
            throw new \RuntimeException('Le fichier dépasse la taille maximale autorisée.');
        }

        $originalName = (string) ($file['name'] ?? 'document');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException('Format de fichier non autorisé.');
        }

        $baseName = strtolower((string) pathinfo($originalName, PATHINFO_FILENAME));
        $safeName = preg_replace('/[^a-z0-9]+/', '-', $baseName) ?? 'document';
        $safeName = trim($safeName, '-');
        if ($safeName === '') {
            $safeName = 'document';
        }

        $publicDirectory = trim(str_replace('\\', '/', $publicDirectory), '/');
        $targetDirectory = dirname(__DIR__, 2) . '/public/' . $publicDirectory;

        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
            throw new \RuntimeException('Impossible de créer le dossier de destination.');
        }

        $targetFileName = sprintf(
            '%s-%s-%s.%s',
            $safeName,
            date('YmdHis'),
            bin2hex(random_bytes(4)),
            $extension
        );

        $targetPath = $targetDirectory . '/' . $targetFileName;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException('Impossible d’enregistrer le fichier sur le serveur.');
        }

        return 'public/' . $publicDirectory . '/' . $targetFileName;
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale autorisée.',
            UPLOAD_ERR_PARTIAL => 'Le fichier a été envoyé partiellement.',
            UPLOAD_ERR_NO_TMP_DIR => 'Le dossier temporaire est manquant sur le serveur.',
            UPLOAD_ERR_CANT_WRITE => 'Le serveur ne peut pas écrire le fichier envoyé.',
            UPLOAD_ERR_EXTENSION => 'Une extension PHP a bloqué l’envoi du fichier.',
            default => 'Une erreur est survenue pendant l’envoi du fichier.',
        };
    }
}
