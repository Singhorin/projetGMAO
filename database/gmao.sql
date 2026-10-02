CREATE DATABASE IF NOT EXISTS gmao_bons_travail CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gmao_bons_travail;

CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL
);

CREATE TABLE equipements (
    id_equipement INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    categorie VARCHAR(80) NOT NULL,
    localisation VARCHAR(100) NOT NULL,
    date_mise_service DATE,
    statut VARCHAR(30) NOT NULL DEFAULT 'En marche'
);

CREATE TABLE demandes_intervention (
    id_di INT AUTO_INCREMENT PRIMARY KEY,
    id_equipement INT NOT NULL,
    id_demandeur INT NOT NULL,
    description TEXT NOT NULL,
    urgence VARCHAR(20) NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'En attente',
    date_demande DATETIME NOT NULL,
    FOREIGN KEY (id_equipement) REFERENCES equipements(id_equipement),
    FOREIGN KEY (id_demandeur) REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE bons_travail (
    id_bt INT AUTO_INCREMENT PRIMARY KEY,
    numero_bt VARCHAR(20) NOT NULL UNIQUE,
    id_equipement INT NOT NULL,
    id_di INT NULL,
    id_technicien INT NULL,
    type_bt VARCHAR(20) NOT NULL,
    titre VARCHAR(150) NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'Créé',
    date_creation DATETIME NOT NULL,
    temps_passe DECIMAL(5,2) NULL,
    cause_panne TEXT NULL,
    taches_realisees TEXT NULL,
    FOREIGN KEY (id_equipement) REFERENCES equipements(id_equipement),
    FOREIGN KEY (id_di) REFERENCES demandes_intervention(id_di),
    FOREIGN KEY (id_technicien) REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE planifications (
    id_planification INT AUTO_INCREMENT PRIMARY KEY,
    id_equipement INT NOT NULL,
    titre VARCHAR(150) NOT NULL,
    periodicite VARCHAR(50) NOT NULL,
    prochaine_date DATE NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'Planifié',
    FOREIGN KEY (id_equipement) REFERENCES equipements(id_equipement)
);

INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Jean Dupont', 'admin.maint@usine.com', 'Admin2026!', 'Responsable'),
('Pierre Martin', 'tech.pierre@usine.com', 'Tech2026!', 'Technicien'),
('Lucas Bernard', 'op.lucas@usine.com', 'Op2026!', 'Opérateur');

INSERT INTO equipements (code, nom, categorie, localisation, date_mise_service, statut) VALUES
('ROB-01', 'Robot de soudage KUKA', 'Robotique', 'Ligne d''assemblage A', '2022-01-15', 'En marche'),
('PRE-02', 'Presse hydraulique 50T', 'Machine-outil', 'Atelier forge', '2021-05-10', 'En marche'),
('COM-01', 'Compresseur d''air ATLAS', 'Infrastructure', 'Local technique', '2020-09-20', 'En marche');

INSERT INTO demandes_intervention (id_equipement, id_demandeur, description, urgence, statut, date_demande) VALUES
(2, 3, 'Fuite d''huile majeure sous le vérin principal, la pression chute.', 'Critique', 'En attente', NOW());

INSERT INTO planifications (id_equipement, titre, periodicite, prochaine_date, statut) VALUES
(3, 'Remplacement des filtres à air', 'Tous les mois', CURDATE(), 'Planifié');
