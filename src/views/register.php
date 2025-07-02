<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../services/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['user'] ?? '');
    $email = trim($_POST['mail'] ?? '');
    $mot_de_passe = trim($_POST['password'] ?? '');

    if ($identifiant === '' || $email === '' || $mot_de_passe === '') {
        $message = "⚠️ Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM Utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $message = "❌ Cette adresse email est déjà utilisée.";
            } else {
                $stmt = $db->prepare("SELECT COUNT(*) FROM Utilisateur WHERE identifiant = ?");
                $stmt->execute([$identifiant]);
                if ($stmt->fetchColumn() > 0) {
                    $message = "❌ Ce nom d'utilisateur est déjà pris.";
                } else {
                    $stmt = $db->prepare("INSERT INTO Utilisateur (identifiant, email, mot_de_passe) VALUES (?, ?, ?)");
                    $success = $stmt->execute([
                        $identifiant,
                        $email,
                        password_hash($mot_de_passe, PASSWORD_DEFAULT)
                    ]);

                    if ($success) {
                        $_SESSION['username'] = $identifiant;
                        header('Location: /?page=home');
                        exit;
                    } else {
                        $message = "❌ Une erreur est survenue.";
                    }
                }
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
    <title>Créer un compte</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section class="login">
        <form method="POST" class="login-form">
            <h2>Créer un compte</h2>
            <?php if ($message): ?>
                <p style="color:red;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <input type="text" name="user" placeholder="Nom d'utilisateur" required>
            <input type="text" name="mail" placeholder="Adresse E-MAIL" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Créer un compte">
            <a href="/?page=signin">Déjà client ? <span class="connect">Connectez-vous</span></a>
        </form>
    </section>
</body>
</html>
