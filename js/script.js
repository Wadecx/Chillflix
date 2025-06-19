const boutonRecherche = document.getElementById("bouton-recherche");
const champRecherche = document.getElementById("champ-recherche");
const submitRecherche = document.getElementById("submit-recherche");
const conteneurMedias = document.getElementById("conteneur-medias");
const barreNavigation = document.querySelector(".barre-de-navigation");

let paginationDiv = document.getElementById("pagination");
if (!paginationDiv) {
  paginationDiv = document.createElement("div");
  paginationDiv.id = "pagination";
  paginationDiv.style = "text-align:center; margin: 20px 0;";
  conteneurMedias.parentNode.insertBefore(
    paginationDiv,
    conteneurMedias.nextSibling
  );
}

let films = [];
let recherche = "";
let pageActuelle = 1;
const ELEMENTS_PAR_PAGE = 5;
let timerRechercheAuto = null;

// Bouton recherche toggle barre recherche
boutonRecherche.addEventListener("click", () => {
  champRecherche.classList.toggle("visible");

  if (champRecherche.classList.contains("visible")) {
    champRecherche.setAttribute("aria-hidden", "false");
    champRecherche.focus();
  } else {
    champRecherche.setAttribute("aria-hidden", "true");
    champRecherche.value = "";
    recherche = "";
    films = [];
    pageActuelle = 1;
    afficherFilms();
    afficherPagination();
  }
});

// Focus/Blur gestion logo
champRecherche.addEventListener("focus", () => {
  barreNavigation.classList.add("recherche-active");
});

champRecherche.addEventListener("blur", () => {
  barreNavigation.classList.remove("recherche-active");
});

// Saisie input avec debounce pour recherche auto après 3 secondes
champRecherche.addEventListener("input", (e) => {
  recherche = e.target.value.trim();

  if (timerRechercheAuto) clearTimeout(timerRechercheAuto);

  if (recherche !== "") {
    timerRechercheAuto = setTimeout(() => {
      pageActuelle = 1;
      callApi();
    }, 3000);
  } else {
    // Si vide, on vide résultats
    films = [];
    afficherFilms();
    afficherPagination();
  }
});

// Soumission bouton recherche (clic)
submitRecherche.addEventListener("click", (e) => {
  e.preventDefault();
  if (recherche !== "") {
    pageActuelle = 1;
    callApi();
  }
});

// Fonction pour appeler l'API OMDB
function callApi() {
  const url = `https://www.omdbapi.com/?apikey=49ecc849&s=${encodeURIComponent(
    recherche
  )}&type=movie`;

  fetch(url)
    .then((res) => res.json())
    .then((data) => {
      if (data.Response === "True") {
        films = data.Search;
        afficherFilms();
        afficherPagination();
      } else {
        alert("Aucun résultat : " + data.Error);
        films = [];
        afficherFilms();
        afficherPagination();
      }
    })
    .catch((err) => alert("Erreur : " + err));
}

// Affichage films
function afficherFilms() {
  conteneurMedias.innerHTML = "";

  if (films.length === 0) {
    conteneurMedias.innerHTML = "<p>Aucun film à afficher.</p>";
    return;
  }
  const debut = (pageActuelle - 1) * ELEMENTS_PAR_PAGE;
  const fin = Math.min(debut + ELEMENTS_PAR_PAGE, films.length);

  for (let i = debut; i < fin; i++) {
    const film = films[i];
    const carte = document.createElement("div");
    carte.classList.add("carte-film");

    carte.innerHTML = `
      <img class="affiche-film" data-id="${film.imdbID}" src="${
      film.Poster !== "N/A" ? film.Poster : "https://via.placeholder.com/150"
    }" alt="${film.Title}" />
      <h2>${film.Title}</h2>
      <p>${film.Year}</p>
    `;

    conteneurMedias.appendChild(carte);
  }

  // Ajouter événement click sur affiches
  document.querySelectorAll(".affiche-film").forEach((img) => {
    img.addEventListener("click", async () => {
      const imdbID = img.getAttribute("data-id");

      const res = await fetch(
        `https://www.omdbapi.com/?apikey=49ecc849&i=${imdbID}&plot=full`
      );
      const film = await res.json();

      if (film.Response === "True") {
        injecterModal(film);
      } else {
        alert("Erreur de récupération des détails.");
      }
    });
  });
}

// Affichage pagination
function afficherPagination() {
  paginationDiv.innerHTML = "";

  if (films.length <= ELEMENTS_PAR_PAGE) return;

  const totalPages = Math.ceil(films.length / ELEMENTS_PAR_PAGE);

  const btnPrec = document.createElement("button");
  btnPrec.textContent = "← Précédent";
  btnPrec.disabled = pageActuelle === 1;
  btnPrec.style.marginRight = "10px";
  btnPrec.addEventListener("click", () => {
    if (pageActuelle > 1) {
      pageActuelle--;
      afficherFilms();
      afficherPagination();
    }
  });

  const btnSuiv = document.createElement("button");
  btnSuiv.textContent = "Suivant →";
  btnSuiv.disabled = pageActuelle === totalPages;
  btnSuiv.addEventListener("click", () => {
    if (pageActuelle < totalPages) {
      pageActuelle++;
      afficherFilms();
      afficherPagination();
    }
  });

  const pageInfo = document.createElement("span");
  pageInfo.textContent = ` Page ${pageActuelle} / ${totalPages} `;
  pageInfo.style.margin = "0 10px";

  paginationDiv.appendChild(btnPrec);
  paginationDiv.appendChild(pageInfo);
  paginationDiv.appendChild(btnSuiv);
}

// Injection modal détail film
function injecterModal(film) {
  const ancienModal = document.getElementById("fenetre-details");
  if (ancienModal) ancienModal.remove();

  const modal = document.createElement("div");
  modal.id = "fenetre-details";
  modal.style = `
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  `;

  modal.innerHTML = `
    <div style="
      background: white;
      color: black;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      width: 90%;
      position: relative;
      max-height: 90vh;
      overflow-y: auto;
    ">
      <button id="fermer-details" style="
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        background: none;
        border: none;
        cursor: pointer;
      ">✖</button>

      <img src="${film.Poster}" alt="${film.Title}" style="
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 10px;
      " />

      <h3 style="margin-top: 15px;">${film.Title}</h3>
      <p><strong>Année :</strong> ${film.Year}</p>
      <p><strong>Description :</strong> ${film.Plot}</p>
    </div>
  `;

  document.body.appendChild(modal);

  document.getElementById("fermer-details").addEventListener("click", () => {
    modal.remove();
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.remove();
  });
}
