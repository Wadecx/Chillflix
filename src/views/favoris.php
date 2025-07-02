<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../services/database.php'; 


if (!isset($_SESSION['username'])) {
    header('Location: /?page=signin');
    exit;
}

$username = $_SESSION['username'];

$databasePath = getenv('DATABASE_PATH') ?: __DIR__ . '/../SQL/bdd.db';

try {
    $db = new PDO('sqlite:' . $databasePath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur base de données : " . htmlspecialchars($e->getMessage());
    exit;
}


$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAjax) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['action'], $data['oeuvre_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Données manquantes']);
        exit;
    }

    $action = $data['action'];
    $oeuvre_id = $data['oeuvre_id'];

    if (!is_string($oeuvre_id) || empty($oeuvre_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID oeuvre invalide']);
        exit;
    }

    if ($action === 'add') {
        $stmt = $db->prepare("SELECT COUNT(*) FROM favoris WHERE utilisateur_id = :user AND oeuvre_id = :oeuvre");
        $stmt->execute([':user' => $username, ':oeuvre' => $oeuvre_id]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $db->prepare("INSERT INTO favoris (utilisateur_id, oeuvre_id) VALUES (:user, :oeuvre)");
            $stmt->execute([':user' => $username, ':oeuvre' => $oeuvre_id]);
            echo json_encode(['success' => 'Favori ajouté']);
        } else {
            echo json_encode(['info' => 'Déjà en favori']);
        }
        exit;
    } elseif ($action === 'remove') {
        $stmt = $db->prepare("DELETE FROM favoris WHERE utilisateur_id = :user AND oeuvre_id = :oeuvre");
        $stmt->execute([':user' => $username, ':oeuvre' => $oeuvre_id]);
        echo json_encode(['success' => 'Favori retiré']);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Action invalide']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $isAjax) {
    header('Content-Type: application/json');

    $stmt = $db->prepare("SELECT oeuvre_id FROM favoris WHERE utilisateur_id = :user");
    $stmt->execute([':user' => $username]);
    $favoris = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['favoris' => $favoris]);
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Mes favoris - ChillFlix</title>
<link href="css/favoris.css" rel="stylesheet" />
</head>
<body>

<nav>
  <a href="?page=home">Accueil</a> |
  <a href="?page=logout">Déconnexion</a>
</nav>

<h1>Liste de mes films favoris</h1>

<div id="films">
 
</div>

<script>
const apiFavorisUrl = '?page=favoris';

async function fetchFavoris() {
  const response = await fetch(apiFavorisUrl, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
  if (!response.ok) return [];
  const data = await response.json();
  return data.favoris || [];
}

async function fetchDetails(imdbID) {
  const res = await fetch(`https://www.omdbapi.com/?apikey=49ecc849&i=${imdbID}&plot=short`);
  const film = await res.json();
  if (film.Response === "True") return film;
  return null;
}

async function afficherFavoris() {
  const favoris = await fetchFavoris();
  const container = document.getElementById('films');
  container.innerHTML = '';

  if (favoris.length === 0) {
    container.textContent = 'Vous n\'avez aucun favori.';
    return;
  }

  for (const id of favoris) {
    const film = await fetchDetails(id);
    if (!film) continue;

    const div = document.createElement('div');
    div.classList.add('film');
    div.innerHTML = `
      <strong>${film.Title}</strong> (${film.Year})
      <button class="favori-btn" data-id="${id}">Retirer des favoris</button>
    `;
    container.appendChild(div);

    div.querySelector('button.favori-btn').addEventListener('click', async () => {
      try {
        const response = await fetch(apiFavorisUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ action: 'remove', oeuvre_id: id })
        });
        const json = await response.json();
        if (json.success) {
          afficherFavoris();
        } else {
          alert('Erreur : ' + (json.error || 'Impossible de retirer le favori'));
        }
      } catch {
        alert('Erreur réseau');
      }
    });
  }
}

window.addEventListener('DOMContentLoaded', afficherFavoris);
</script>

</body>
</html>
