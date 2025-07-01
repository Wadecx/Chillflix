<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Normalisation de l'URI (sans slash final sauf racine)
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') {
    $uri = '/';
}

// Routes publiques accessibles sans authentification
$publicRoutes = ['/signin', '/logout'];

// DEBUG - Affiche les infos utiles (à enlever quand ça marche)
var_dump('URI:', $uri);
var_dump('Session username:', $_SESSION['username'] ?? null);
var_dump('Public routes:', $publicRoutes);

// Contrôle d’accès : si pas connecté et route privée, redirige vers /signin
if (!isset($_SESSION['username']) && !in_array($uri, $publicRoutes)) {
    header('Location: /signin');
    exit;
}

// Si connecté et qu'on essaie d'accéder à /signin, redirige vers /
if (isset($_SESSION['username']) && $uri === '/signin') {
    header('Location: /');
    exit;
}

// Routeur principal
switch ($uri) {
    case '/signin':
        require __DIR__ . '/views/signin.php';
        break;

    case '/logout':
        session_destroy();
        header('Location: /signin');
        exit;

    case '/':
        require __DIR__ . '/../public/index.php';
        break;

    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
