DROP TABLE IF EXISTS Commentaire;
DROP TABLE IF EXISTS Favori;
DROP TABLE IF EXISTS Oeuvre_Acteur;
DROP TABLE IF EXISTS Oeuvre_Scenariste;
DROP TABLE IF EXISTS Oeuvre_Realisateur;
DROP TABLE IF EXISTS Oeuvre_Pays;
DROP TABLE IF EXISTS Oeuvre_Langue;
DROP TABLE IF EXISTS Oeuvre_Genre;
DROP TABLE IF EXISTS Personne;
DROP TABLE IF EXISTS Pays;
DROP TABLE IF EXISTS Langue;
DROP TABLE IF EXISTS Genre;
DROP TABLE IF EXISTS Oeuvre;
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  identifiant TEXT UNIQUE NOT NULL,
  mot_de_passe TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL
);

CREATE TABLE Oeuvre (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titre TEXT NOT NULL,
  annee INTEGER,
  duree INTEGER,
  description TEXT,
  affiche TEXT,
  type TEXT CHECK(type IN ('film', 'serie')) NOT NULL
);

CREATE TABLE Genre (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT UNIQUE NOT NULL
);

CREATE TABLE Langue (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT UNIQUE NOT NULL
);

CREATE TABLE Pays (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT UNIQUE NOT NULL
);

CREATE TABLE Personne (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT NOT NULL
);

CREATE TABLE Oeuvre_Genre (
  oeuvre_id INTEGER,
  genre_id INTEGER,
  PRIMARY KEY (oeuvre_id, genre_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (genre_id) REFERENCES Genre(id)
);

CREATE TABLE Oeuvre_Langue (
  oeuvre_id INTEGER,
  langue_id INTEGER,
  PRIMARY KEY (oeuvre_id, langue_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (langue_id) REFERENCES Langue(id)
);

CREATE TABLE Oeuvre_Pays (
  oeuvre_id INTEGER,
  pays_id INTEGER,
  PRIMARY KEY (oeuvre_id, pays_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (pays_id) REFERENCES Pays(id)
);

CREATE TABLE Oeuvre_Realisateur (
  oeuvre_id INTEGER,
  personne_id INTEGER,
  PRIMARY KEY (oeuvre_id, personne_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (personne_id) REFERENCES Personne(id)
);

CREATE TABLE Oeuvre_Scenariste (
  oeuvre_id INTEGER,
  personne_id INTEGER,
  PRIMARY KEY (oeuvre_id, personne_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (personne_id) REFERENCES Personne(id)
);

CREATE TABLE Oeuvre_Acteur (
  oeuvre_id INTEGER,
  personne_id INTEGER,
  PRIMARY KEY (oeuvre_id, personne_id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id),
  FOREIGN KEY (personne_id) REFERENCES Personne(id)
);

CREATE TABLE Favori (
  utilisateur_id INTEGER,
  oeuvre_id INTEGER,
  PRIMARY KEY (utilisateur_id, oeuvre_id),
  FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id)
);

CREATE TABLE Commentaire (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  utilisateur_id INTEGER,
  oeuvre_id INTEGER,
  texte TEXT NOT NULL,
  note INTEGER CHECK(note BETWEEN 1 AND 5),
  FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id),
  FOREIGN KEY (oeuvre_id) REFERENCES Oeuvre(id)
);
