CREATE database brasil_burger_db;

Use brasil_burger_db;

CREATE TABLE Burger (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT
);

CREATE TABLE Complement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT
);

CREATE TABLE Menu (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT
);

CREATE TABLE Client (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL
);

CREATE TABLE Zone (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL
);

CREATE TABLE Livreur (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    zone_id INT REFERENCES Zone(id)
);

CREATE TABLE Gestionnaire (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    login VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Paiement (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    methode VARCHAR(50) NOT NULL CHECK (methode IN ('Wave', 'OM'))
);

CREATE TABLE LigneCommande (
    id SERIAL PRIMARY KEY,
    burger_id INT REFERENCES Burger(id),
    menu_id INT REFERENCES Menu(id),
    complement_id INT REFERENCES Complement(id),
    quantite INT NOT NULL
);

CREATE TABLE Commande (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES Client(id),
    etat VARCHAR(50) NOT NULL DEFAULT 'En cours',
    date DATE NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('Sur place', 'A emporter', 'Livraison')),
    zone_id INT REFERENCES Zone(id),
    livreur_id INT REFERENCES Livreur(id),
    paiement_id INT REFERENCES Paiement(id)
);

