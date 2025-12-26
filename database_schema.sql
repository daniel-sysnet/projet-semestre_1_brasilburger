-- Schéma de la base de données pour Brasil Burger

-- Table Burger
CREATE TABLE IF NOT EXISTS Burger (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    description TEXT,
    actif BOOLEAN DEFAULT true
);

-- Table Menu
CREATE TABLE IF NOT EXISTS Menu (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    description TEXT,
    actif BOOLEAN DEFAULT true
);

-- Table Complement
CREATE TABLE IF NOT EXISTS Complement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    description TEXT,
    actif BOOLEAN DEFAULT true
);

-- Mise à jour des données existantes
UPDATE Burger SET actif = true WHERE actif IS NULL;
UPDATE Menu SET actif = true WHERE actif IS NULL;
UPDATE Complement SET actif = true WHERE actif IS NULL;

