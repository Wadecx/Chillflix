


// Bon courage pour le code Adelaide 😁🔥


const boutonRecherche = document.getElementById("bouton-recherche");
const champRecherche = document.getElementById("champ-recherche");
const conteneurMedias = document.getElementById("conteneur-medias");
const fenetreDetails = document.getElementById("fenetre-details");
const imageDetails = document.getElementById("image-details");
const titreDetails = document.getElementById("titre-details");
const descriptionDetails = document.getElementById("description-details");
const anneeDetails = document.getElementById("annee-details");
const boutonFermerDetails = document.getElementById("fermer-details");

let films = [];

boutonRecherche.addEventListener("click", () => {
  champRecherche.classList.add("transition");
  champRecherche.classList.toggle("visible");

  if (champRecherche.classList.contains("visible")) {
    champRecherche.setAttribute("aria-hidden", "false");
    champRecherche.focus();
  } else {
    champRecherche.setAttribute("aria-hidden", "true");
    champRecherche.value = "";
    afficherFilms(films);
  }
});


document.getElementById("menu-accueil").addEventListener("click", () => {
  afficherFilms(films);
});

document.getElementById("menu-films").addEventListener("click", () => {
  const filmsFiltres = films.filter(f => f.type.toLowerCase() === "film");
  afficherFilms(filmsFiltres);
});

document.getElementById("menu-series").addEventListener("click", () => {
  const seriesFiltres = films.filter(f => f.type.toLowerCase() === "série" || f.type.toLowerCase() === "serie");
  afficherFilms(seriesFiltres);
});

function afficherFilms(liste) {
  if (conteneurMedias.children.length === 0) {
    afficherCartes(liste);
    return;
  }

  Array.from(conteneurMedias.children).forEach(carte => carte.classList.add("animate-out"));

  setTimeout(() => {
    afficherCartes(liste);
  }, 400);
}

function afficherCartes(liste) {
  conteneurMedias.innerHTML = "";
  if (liste.length === 0) {
    conteneurMedias.innerHTML = "<p>Aucun résultat trouvé.</p>";
    return;
  }

  liste.forEach((item, index) => {
    const carte = document.createElement("div");
    carte.classList.add("media-card", "animate-in");
    carte.style.animationDelay = `${index * 0.05}s`;

    carte.innerHTML = `
      <img src="${item.image}" alt="${item.title}" />
      <div class="info">
        <h3>${item.title}</h3>
        <p>${item.type} • ${item.year}</p>
      </div>
    `;

    carte.addEventListener("click", () => {
      ouvrirDetails(item);
    });

    conteneurMedias.appendChild(carte);
  });
}

const detailsHTML = `
  <div id="fenetre-details" class="fenetre-details" aria-hidden="true">
    <div class="contenu-details">
      <button id="fermer-details" aria-label="Fermer la fenêtre de détails">&times;</button>
      <img id="image-details" src="" alt="" />
      <h3 id="titre-details"></h3>
      <p id="description-details"></p>
      <p id="annee-details"></p>
    </div>
  </div>
`;
document.body.insertAdjacentHTML('beforeend', detailsHTML);
function ouvrirDetails(film) {
  imageDetails.src = film.image;
  imageDetails.alt = film.title;
  titreDetails.textContent = film.title;
  descriptionDetails.textContent = film.description || "Description non disponible.";
  anneeDetails.textContent = `Année : ${film.year}`;
  fenetreDetails.setAttribute("aria-hidden", "false");

  fenetreDetails.classList.add("ouvrir-animation");
  setTimeout(() => fenetreDetails.classList.remove("ouvrir-animation"), 300);
}

boutonFermerDetails.addEventListener("click", () => {
  fenetreDetails.setAttribute("aria-hidden", "true");
});

fenetreDetails.addEventListener("click", e => {
  if (e.target === fenetreDetails) {
    fenetreDetails.setAttribute("aria-hidden", "true");
  }
});

fetch('/js/films.json')
  .then(response => {
    if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
    return response.json();
  })
  .then(data => {
    films = data;
    afficherFilms(films);
  })
  .catch(error => {
    console.error('Erreur lors du chargement des films:', error);
    conteneurMedias.innerHTML = "<p>Impossible de charger la liste des films.</p>";
  });

champRecherche.addEventListener("input", e => {
  const recherche = e.target.value.toLowerCase();
  const filtres = films.filter(film => film.title.toLowerCase().includes(recherche));
  afficherFilms(filtres);
});