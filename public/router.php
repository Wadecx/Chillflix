<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'home';

if (!isset($_SESSION['username']) && !in_array($page, ['signin', 'register'])) {
    header('Location: /router.php?page=signin');
    exit;
}

switch ($page) {
    case 'home':
        require_once __DIR__ . '/../src/views/home.php';
        break;
    case 'login':
        require_once __DIR__ . '/../src/views/login.php';
        break;
    case 'signin':
        require_once __DIR__ . '/../src/views/signin.php';
        break;
    case 'register':
        require_once __DIR__ . '/../src/views/register.php';
        break;
    case 'favoris':
        require_once __DIR__ . '/../src/views/favoris.php';
        break;
    case 'logout':
        require_once __DIR__ . '/../src/views/logout.php';
        break;
    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
