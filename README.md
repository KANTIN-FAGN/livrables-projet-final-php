# Projet Portfolio - Gestion des Utilisateurs et des Compétences

## Présentation du Projet

Ce projet est une application web développée en PHP & MySQL permettant aux utilisateurs de :

- [X] Gérer leur profil (inscription, connexion, mise à jour des informations).
- [X] Ajouter et modifier leurs compétences parmi celles définies par un administrateur.
- [X] Ajouter et gérer leurs projets (titre, description, image et lien).
- [X] Un administrateur peut gérer les compétences disponibles.

## Fonctionnalités Implémentées

### Authentification & Gestion des Comptes

- [X] Inscription avec validation des champs
- [ ] Connexion sécurisée avec sessions et option "Se souvenir de moi"
- [X] Gestion des rôles (Admin / Utilisateur)
- [X] Mise à jour des informations utilisateur
- [ ] Réinitialisation du mot de passe
- [X] Déconnexion sécurisée

### Gestion des Compétences

- [X] L’administrateur peut gérer les compétences proposées
- [X] Un utilisateur peut sélectionner ses compétences parmi celles disponibles
- [X] Niveau de compétence défini sur une échelle (débutant → expert)

### Gestion des Projets

- [X] Ajout, modification et suppression de projets
- [X] Chaque projet contient : Titre, Description, Image, Lien externe
- [X] Upload sécurisé des images avec restrictions de format et taille
- [X] Affichage structuré des projets

### Sécurité

- [ ] Protection contre XSS, CSRF et injections SQL
- [X] Hachage sécurisé des mots de passe
- [X] Gestion des erreurs utilisateur avec affichage des messages et conservation des champs remplis
- [X] Expiration automatique de la session après inactivité

## Installation et Configuration

### Prérequis

- Serveur local (XAMPP, WAMP, etc.)
- PHP 8.x et MySQL
- Un navigateur moderne

### Étapes d’Installation

1. Cloner le projet sur votre serveur local :
   ```sh
   git clone https://github.com/KANTIN-FAGN/livrables-projet-final-php.git
   cd livrables-projet-final-php
   ```
2. Importer la base de données : Exécuter le script SQL dans le fichier `config/database.sql` dans MyPHPMyAdmin


3. Configurer la connexion à la base de données :
   Modifier le fichier `config/Database.php` :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'projetb2');
   define('DB_USER', 'projetb2');
   define('DB_PASS', 'password');
   define('DB_PORT', 3306);
   ```

4. Configurer les variables d'environnements :
   Créer un fichier a la racine du projet `.env` :
   Insérer les variables dans le `.env` depuis le `.env.example` :
   Créer un fichier a la racine du projet `.env` :

5. Démarrer le serveur PHP et tester l'application :
   ```sh
   php start.php
   ```
   Puis accéder à l'application via `http://localhost:8000`

## Comptes de Test

### Compte Administrateur

- **Email** : admin@example.com
- **Mot de passe** : password

### Comptes Utilisateurs

- **Utilisateur 1** :  
  Email       : user1@example.com  
  Mot de passe : password


- **Utilisateur 2** :  
  Email       : user2@example.com  
  Mot de passe : password


- **Utilisateur 3** :  
  Email       : user3@example.com  
  Mot de passe : password

## Structure du Projet

UN exemple de structure possible (la première ligne doit respecter cette structure).

```
/config/database.php -> Configuration de la base de données
/config/database.sql -> Script SQL pour initialiser la base de données
/src/models/         -> Classes PHP (User, Auth, Project, Skill)
/src/controllers/    -> Gestion des requêtes et logiques métier
/src/core/           -> Fournit les classes et utilitaires de base pour le fonctionnement global de l'application.
/src/inculdes/       -> Regroupe les fichiers et utilitaires PHP inclus dans plusieurs zones de l'application.
/src/middlewares/    -> Gère les traitements intermédiaires (ex. : authentification, validation des requêtes).
/src/public/         -> Images et assets du projet
/src/routes/         -> Définit les routes utilisées par l'application pour relier URL et actions.
/src/views/          -> Interfaces utilisateur (HTML, CSS, Bootstrap)
```

## Technologies Utilisées

- **Backend** : **PHP / MYSQL**
- **Frontend** : **PHP / SCSS**
- **Sécurité** : **SHA256**
- **Gestion du Projet** : **GITHUB**

## Licence

Ce projet est sous licence MIT.

## Contact

Une question ou un bug ? Contactez-moi : kantin.fagn@gmail.com
