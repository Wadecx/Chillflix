<?php
try {
    $dbPath = __DIR__ . '/../SQL/bdd.db'; // __DIR__ = dossier de ce fichier
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
