<?php
// Configuration générale du site
define('SITE_NAME', 'Portfolio - Expert Full Stack');
define('SITE_URL', 'http://localhost:8080');
define('ADMIN_EMAIL', 'contact@portfolio.com');

// Configuration base de données SQLite
define('DB_PATH', __DIR__ . '/../data/portfolio.db');

// Configuration sécurité
define('CSRF_TOKEN_NAME', 'csrf_token');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB

// Activation du reporting d'erreurs en développement
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Démarrage de la session si pas encore fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Génération token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>