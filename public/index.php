<?php
// Point d entree du site : bootstrap des dependances puis declaration de toutes les routes.
// Front controller

declare(strict_types=1);

// Chemins de base
$rootPath = dirname(__DIR__);
session_start();

// Autoload très simple (pour ce projet)
spl_autoload_register(function (string $class): void {
    $prefixes = [
        'Core\\' => __DIR__ . '/../core/',
        'App\\Controllers\\' => __DIR__ . '/../app/Controllers/',
        'App\\Models\\' => __DIR__ . '/../app/Models/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
            }
        }
    }
});

require $rootPath . '/core/View.php';
require $rootPath . '/core/Router.php';
require $rootPath . '/core/Security.php';

use Core\Router;

// Définition des routes principales
$router = new Router();

$router->get('/', 'App\\Controllers\\IntroController@index');
$router->get('/entry', 'App\\Controllers\\IntroController@index');
$router->get('/entree', 'App\\Controllers\\IntroController@index');
$router->get('/accueil', 'App\\Controllers\\HomeController@index');
$router->get('/offres', 'App\\Controllers\\OfferController@index');
$router->get('/offres/detail', 'App\\Controllers\\OfferController@show');
$router->get('/entreprises', 'App\\Controllers\\CompanyController@index');
$router->get('/entreprises/detail', 'App\\Controllers\\CompanyController@show');
$router->get('/login', 'App\\Controllers\\AuthController@login');
$router->post('/login', 'App\\Controllers\\AuthController@handleLogin');
$router->get('/login/etudiant', 'App\\Controllers\\AuthController@studentLogin');
$router->post('/login/etudiant', 'App\\Controllers\\AuthController@handleStudentLogin');
$router->get('/login/pilote', 'App\\Controllers\\AuthController@pilotLogin');
$router->post('/login/pilote', 'App\\Controllers\\AuthController@handlePilotLogin');
$router->get('/login/admin', 'App\\Controllers\\AuthController@adminLogin');
$router->post('/login/admin', 'App\\Controllers\\AuthController@handleAdminLogin');
$router->get('/register', 'App\\Controllers\\AuthController@showRegister');
$router->post('/register', 'App\\Controllers\\AuthController@register');
$router->get('/dashboard-etudiant', 'App\\Controllers\\StudentController@dashboard');
$router->get('/dashboard-pilote', 'App\\Controllers\\PilotController@dashboard');
$router->get('/dashboard-admin', 'App\\Controllers\\AdminController@dashboard');
$router->get('/wishlist', 'App\\Controllers\\WishlistController@index');
$router->post('/wishlist/toggle', 'App\\Controllers\\WishlistController@toggle');
$router->get('/candidatures', 'App\\Controllers\\ApplicationController@index');
$router->get('/candidatures/nouvelle', 'App\\Controllers\\ApplicationController@create');
$router->post('/candidatures', 'App\\Controllers\\ApplicationController@store');
$router->get('/etudiant/avis', 'App\\Controllers\\StudentController@feedback');
$router->post('/etudiant/avis', 'App\\Controllers\\StudentController@storeFeedback');
$router->get('/etudiant/entreprises/evaluer', 'App\\Controllers\\StudentController@companyReview');
$router->post('/etudiant/entreprises/evaluer', 'App\\Controllers\\StudentController@storeCompanyReview');
$router->get('/etudiant/documents', 'App\\Controllers\\StudentController@documents');
$router->post('/etudiant/documents', 'App\\Controllers\\StudentController@saveDocuments');
$router->get('/pilote/offres/ajouter', 'App\\Controllers\\PilotController@createOffer');
$router->post('/pilote/offres/ajouter', 'App\\Controllers\\PilotController@storeOffer');
$router->get('/pilote/avis', 'App\\Controllers\\PilotController@reviews');
$router->get('/pilote/relances', 'App\\Controllers\\PilotController@followUps');
$router->get('/pilote/entreprises', 'App\\Controllers\\PilotController@companies');
$router->get('/admin/offres/modifier', 'App\\Controllers\\AdminController@editOffers');
$router->post('/admin/offres/modifier', 'App\\Controllers\\AdminController@updateOffer');
$router->get('/admin/comptes', 'App\\Controllers\\AdminController@accounts');
$router->get('/admin/moderation', 'App\\Controllers\\AdminController@moderation');
$router->get('/admin/qualite', 'App\\Controllers\\AdminController@quality');
$router->get('/mentions-legales', 'App\\Controllers\\LegalController@mentions');
 $router->get('/logout', 'App\\Controllers\\AuthController@logout');

// Route par défaut si aucune ne correspond
try {
    $router->dispatch();
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Erreur serveur</h1>';
    if ($_SERVER['APP_ENV'] ?? 'prod' === 'dev') {
        echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</pre>';
    }
}

