<?php
// Configuration generale de l'application : URL, base de donnees et options de securite.

return [
    'app_name' => 'Web4Stage',
    'app_public_url' => 'https://web4stage.local/index.php',
    'static_base_url' => 'https://static.web4stage.local',
    'force_https' => true,
    'session_name' => 'WEB4STAGESESSID',
    'session_samesite' => 'Lax',
    'session_secure' => true,
    'legal_owner' => 'Equipe projet Web4Stage - CESI',
    'legal_contact_email' => 'contact@web4stage.local',
    'legal_hosting' => 'Application de demonstration hebergee sur Apache dans un environnement local XAMPP.',
    'db_dsn'  => 'mysql:host=127.0.0.1;port=3307;dbname=web4stage;charset=utf8mb4',
    'db_user' => 'root',
    'db_pass' => '',
];
