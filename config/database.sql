-- Suppression de la base existante si elle existe déjà
DROP DATABASE IF EXISTS projetb2;

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS projetb2;

-- Création et droits pour l'utilisateur de la base de données
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';

USE projetb2;

-- Création de la table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
                                     id INT PRIMARY KEY AUTO_INCREMENT,
                                     email VARCHAR(255) UNIQUE NOT NULL,
    firstname VARCHAR(255)  NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Table de compétences proposées par l'administrateur
CREATE TABLE IF NOT EXISTS skills (
                                      id INT PRIMARY KEY AUTO_INCREMENT,
                                      name VARCHAR(255) UNIQUE NOT NULL
    );

-- Table pour associer les compétences aux utilisateurs
CREATE TABLE IF NOT EXISTS user_skills (
                                           user_id INT NOT NULL,
                                           skill_id INT NOT NULL,
                                           level ENUM('débutant', 'intermédiaire', 'avancé', 'expert') NOT NULL DEFAULT 'débutant',
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
    );

-- Table pour gérer les projets des utilisateurs
CREATE TABLE IF NOT EXISTS projects (
                                        id INT PRIMARY KEY AUTO_INCREMENT,
                                        user_id INT NOT NULL,
                                        title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255),
    external_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

-- Table pour stocker les informations personnelles supplémentaires des utilisateurs
CREATE TABLE IF NOT EXISTS profiles (
                                        user_id INT PRIMARY KEY,
                                        avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    website_link VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

-- Insérer des comptes utilisateurs de test
INSERT INTO users (email, firstname, lastname, password, role)
VALUES
    ('admin@portfolio.com', 'kantin', 'fagniart', '$2y$12$g320TCnG3/gI4cdB1b2Rze94tFg3y3qxvMNvWZPKsTRMTtc40tcK6', 'admin'), -- Mot de passe : password
    ('user1@portfolio.com', 'julien', 'dante','$2y$12$3ZktREnSgEe0Z8rD2wKQ5OTzVhVuRn9CKU3Bbuc2eMPZjQnIpvJaa', 'utilisateur'), -- Mot de passe : password
    ('user2@portfolio.com', 'nathanael', 'pivot','$2y$12$p1NAforHNxqn8mj33s1lguPKf.tltQsA3zupdEbyMA4/NEuwTLbsG', 'utilisateur'), -- Mot de passe : password
    ('user3@portfolio.com', 'vito', 'deriu','$2y$12$SCJu4pNH2If2ToKsZThMte0X2JP3lvK2N8/Q6HOP8gIuMV6rluLne', 'utilisateur'); -- Mot de passe : password

-- Insérer des compétences prédéfinies
INSERT INTO skills (name)
VALUES
    ('HTML'),
    ('CSS'),
    ('JavaScript'),
    ('PHP'),
    ('SQL'),
    ('Design UI/UX'),
    ('SEO'),
    ('Gestion de projets');

-- Assigner des compétences aux utilisateurs (exemple)
INSERT INTO user_skills (user_id, skill_id, level)
VALUES
    (2, 1, 'intermédiaire'), -- HTML - Intermédiaire
    (2, 2, 'avancé'),        -- CSS - Avancé
    (2, 5, 'débutant'),      -- SQL - Débutant
    (2, 6, 'avancé'),        -- Design UI/UX - Avancé
    (3, 3, 'expert'),        -- JavaScript - Expert
    (3, 4, 'intermédiaire'), -- PHP - Intermédiaire
    (3, 5, 'avancé'),        -- SQL - Avancé
    (3, 8, 'débutant'),      -- Gestion de projets - Débutant
    (4, 1, 'débutant'),      -- HTML - Débutant
    (4, 2, 'débutant'),      -- CSS - Débutant
    (4, 3, 'intermédiaire'), -- JavaScript - Intermédiaire
    (4, 7, 'intermédiaire'); -- SEO - Intermédiaire


-- Insérer des projets exemples pour chaque utilisateur
INSERT INTO projects (user_id, title, description, image_path, external_link)
VALUES
    (2, 'Portfolio Personnel', 'Création de mon propre portfolio.', 'images/portfolio1.jpg', 'https://portfolio-user1.com'),
    (2, 'Blog Tech', 'Développement d\'un blog sur les dernières technologies.', 'images/blog_tech.jpg', 'https://techblog-user1.com'),
    (2, 'E-commerce', 'Réalisation d\'un site e-commerce simple.', 'images/ecommerce.jpg', 'https://ecommerce-user1.com'),
    (3, 'Application de Todo List', 'Une application pour gérer les tâches quotidiennes.', 'images/todo_app.jpg', NULL),
    (3, 'Jeu Web', 'Petit jeu réalisé en JavaScript.', 'images/game_project.jpg', 'https://game-user2.com'),
    (3, 'API REST', 'Développement d\'une API REST pour une application mobile.', 'images/api.jpg', NULL),
    (4, 'Blog Personnel', 'Blogging sur les tendances technologiques.', 'images/blog_personal.jpg', 'https://blog-user3.com'),
    (4, 'Outil de Monitoring', 'Développement d\'un outil pour surveiller des systèmes en temps réel.', 'images/monitoring_tool.jpg', NULL),
    (4, 'Portfolio Vitrine', 'Création d\'un portfolio interactif.', 'images/portfolio_vitrine.jpg', 'https://portfolio-vito.com');

-- Insérer des profils supplémentaires pour les utilisateurs
INSERT INTO profiles (user_id, avatar, bio, website_link)
VALUES 
    (2, 'avatars/user1.png', 'Développeur passionné par le web.', 'https://portfolio-user1.com'),
    (3, 'avatars/user2.png', 'Ingénieure et développeuse freelance.', NULL),
    (4, 'avatars/user3.png', 'Stagiaire chez google', NULL);
