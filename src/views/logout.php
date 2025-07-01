<?php
// logout.php

// On démarre la session (uniquement si elle n'est pas déjà démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// On détruit la session et ses données
session_unset();
session_destroy();

// On redirige vers la page de connexion
header('Location: /signin');
exit;
