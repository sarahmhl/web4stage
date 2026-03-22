<?php

declare(strict_types=1);

// Ce controleur de base regroupe les helpers communs utilises par les autres controleurs.

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
     * @return array{id:int,nom:string,prenom:string,email:string,role:string}|null
     */
    protected function currentUser(): ?array
    {
        return Auth::user();
    }
}
