<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/style.css" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="icon" href="img/icons/favicon.png" type="image/x-icon" />
    <title>ChillFlix</title>
  </head>
  <body>
    <header class="barre-de-navigation">
      <div class="conteneur">
        <div class="gauche">
          <img src="img/icons/chillflix.png" alt="Logo ChillFlix" />
        </div>
        <div class="droite">
          <nav class="menu-principal">
            <ul>
              <li><a href="#">Accueil</a></li>
              <li><a href="#">Films</a></li>
              <li><a href="#">Séries</a></li>
              <li><a href="/?page=favoris">Favoris</a></li>
            </ul>
          </nav>

          <div class="recherche">
            <button class="bouton-recherche" id="bouton-recherche" type="button" aria-label="Recherche">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <input
              type="text"
              id="champ-recherche"
              placeholder="Rechercher un film, une série..."
              class="champ-recherche"
            />
          </div>

          <div class="compte-utilisateur">
            <img src="img/icons/account.png" alt="Mon compte" />
            <?php if (isset($_SESSION['username'])): ?>
              <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
              <a href="/?page=logout" class="logout" title="Déconnexion">🚪</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </header>

    <section class="banniere-principale">
      <img src="img/icons/cinema1.jpg" alt="Salle de cinéma" />
      <div class="accueil">
        <h2>Bienvenue sur ChillFlix</h2>
        <p>Des films & séries à portée de clic</p>
      </div>
    </section>

    <section id="section-medias" class="section-medias">
      <h2>Films & Séries</h2>
      <div id="conteneur-medias" class="conteneur-medias"></div>
    </section>

    <div id="fenetre-details" class="fenetre-details cachée">
      <div class="contenu-details">
        <button id="fermer-details" aria-label="Fermer la fenêtre de détails">✖</button>
        <img id="image-details" src="" alt="Affiche du film" />
        <h3 id="titre-details"></h3>
        <p id="description-details"></p>
        <p id="annee-details"></p>
      </div>
    </div>

    <script src="js/script.js"></script>
  </body>
</html>
