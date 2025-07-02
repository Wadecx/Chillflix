<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../services/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiantOuEmail = trim($_POST['user'] ?? '');
    $mot_de_passe = trim($_POST['password'] ?? '');

    if ($identifiantOuEmail === '' || $mot_de_passe === '') {
        $message = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM Utilisateur WHERE identifiant = ? OR email = ?");
            $stmt->execute([$identifiantOuEmail, $identifiantOuEmail]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                $_SESSION['username'] = $utilisateur['identifiant'];
                header('Location: /?page=home');

                exit;
            } else {
                $message = "Identifiant ou mot de passe invalide.";
            }
        } catch (PDOException $e) {
            $message = "Erreur SQL : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section class="login">
        <form method="POST" action="/router.php?page=signin" class="login-form">
            <h2>Connexion</h2>
            <?php if ($message): ?>
                <p style="color:red;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <input type="text" name="user" placeholder="Nom d'utilisateur ou email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
            <a href="/?page=register">Pas de compte ? <span class="connect">Créez-en un ici</span></a>
        </form>
    </section>
</body>
</html>
