-- =================
-- Création des tables
-- =================

CREATE TABLE Burger (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE Complement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE Menu (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
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

CREATE TABLE Commande (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES Client(id),
    etat VARCHAR(50) NOT NULL DEFAULT 'En cours',
    date TIMESTAMP NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('Sur place', 'A emporter', 'Livraison')),
    zone_id INT REFERENCES Zone(id),
    livreur_id INT REFERENCES Livreur(id),
    paiement_id INT REFERENCES Paiement(id)
);

CREATE TABLE LigneCommande (
    id SERIAL PRIMARY KEY,
    commande_id INT REFERENCES Commande(id) ON DELETE CASCADE,
    burger_id INT REFERENCES Burger(id),
    menu_id INT REFERENCES Menu(id),
    complement_id INT REFERENCES Complement(id),
    quantite INT NOT NULL CHECK (quantite > 0)
);

-- =========================
-- Insertion des données
-- =========================

INSERT INTO Burger (nom, description, prix, image) VALUES
('Cheeseburger', 'Burger classique avec fromage', 5000.00, 'cheeseburger.jpg'),
('Big Mac', 'Burger premium avec double viande', 7000.00, 'bigmac.jpg'),
('Chicken Burger', 'Burger au poulet grillé', 6000.00, 'chicken.jpg');

INSERT INTO Complement (nom, description, prix, image) VALUES
('Frites', 'Frites croustillantes', 2000.00, 'frites.jpg'),
('Riz', 'Riz parfumé', 1500.00, 'riz.jpg'),
('Sauce Ketchup', 'Sauce ketchup maison', 500.00, 'ketchup.jpg'),
('Coca Cola', 'Boisson gazeuse Coca Cola', 1500.00, 'coca.jpg'),
('Eau', 'Eau minérale', 1000.00, 'eau.jpg');

INSERT INTO Menu (nom, description, prix, image) VALUES
('Menu Cheeseburger', 'Menu complet avec Cheeseburger', 8500.00, 'menu_cheese.jpg'),
('Menu Big Mac', 'Menu premium avec Big Mac', 10500.00, 'menu_bigmac.jpg');

INSERT INTO Client (nom, prenom, telephone) VALUES
('Dupont', 'Jean', '771234567'),
('Martin', 'Marie', '772345678');

INSERT INTO Zone (nom, prix) VALUES
('Centre-ville', 2000.00),
('Banlieue Nord', 2500.00);

INSERT INTO Livreur (nom, prenom, telephone, zone_id) VALUES
('Livreur1', 'Paul', '773456789', 1),
('Livreur2', 'Sophie', '774567890', 2);

INSERT INTO Gestionnaire (nom, prenom, telephone, login, password) VALUES
('Admin', 'Admin', '775678901', 'admin', 'admin123');

INSERT INTO Paiement (date, montant, methode) VALUES
('2025-12-13', 8500.00, 'Wave'),
('2025-12-13', 10500.00, 'OM');

INSERT INTO Commande (client_id, etat, date, type, zone_id, livreur_id, paiement_id) VALUES
(1, 'Terminee', '2025-12-13', 'Livraison', 1, 1, 1),
(2, 'En cours', '2025-12-13', 'Sur place', NULL, NULL, 2);

INSERT INTO LigneCommande (commande_id, burger_id, menu_id, complement_id, quantite) VALUES
(1, 1, NULL, NULL, 1),
(1, NULL, 1, 1, 1),
(1, NULL, 1, 4, 1);