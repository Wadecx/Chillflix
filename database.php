<?php
try {
    $db = new PDO('sqlite:SQL/bdd.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
echo "Erreur de connexion : " . $e->getMessage();
exit;
}
