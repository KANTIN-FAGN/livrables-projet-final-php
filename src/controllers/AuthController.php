<?php

namespace App\controllers;

use App\core\services\JwtService;

class AuthController
{
    /**
     * Gère l'authentification des utilisateurs.
     * Vérifie les informations d'identification de l'utilisateur, génère un JWT et redirige vers la page principale.
     */
    public function login()
    {
        // Récupération des données POST : email et mot de passe
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        // Validation des champs obligatoires
        if (!$email || !$password) {
            $_SESSION["errors"]["validation"] = "Email ou mot de passe manquant."; // Message d'erreur
            header('Location: /login'); // Erreur 400 : Requête incorrecte
            exit;
        }

        try {
            $authModel = new \App\Models\UserModel(); // Instanciation du modèle utilisateur
            $postModel = new \App\Models\PostModel();

            // Étape 1 : Récupération des informations utilisateur à partir de l'email
            $user = $authModel->findUserByEmail($email);

            // Vérification de l'existence de l'utilisateur et validation du mot de passe
            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION["errors"]["validation"] = "Email ou mot de passe incorrect."; // Message d'erreur
                error_log('test error');
                header('Location: /login'); // Erreur 401 : Non autorisé
                exit;
            }


            // Étape 2 : Récupération du profil utilisateur (avatar, bio, site web)
            $profile = $authModel->findProfileByUserId($user['id']);

            // Définir des valeurs par défaut si l'utilisateur n'a pas renseigné certaines informations
            $avatar = $profile['avatar'] ?? ''; // Avatar par défaut
            $bio = $profile['bio'] ?? 'Pas de biographie renseignée.';
            $website_link = $profile['website_link'] ?? '';

            // Récupération des compétences associées à l'utilisateur
            $skills = $authModel->findSkillsUserByID($user['id']);
            if (is_array($skills)) {
                foreach ($skills as &$skill) {
                    $skill = (array)$skill; // Convertir chaque compétence en tableau
                }
            }

            // Récupération des publications associées à l'utilisateur
            $posts = $postModel->findPostsUserByID($user['id']);
            if (is_array($posts)) {
                foreach ($posts as &$post) {
                    $post = (array)$post; // Convertir chaque publication en tableau
                }
            }

            // Étape 3 : Génération d'un token JWT contenant les informations utilisateur
            $payload = [
                'email' => $user['email'],
                'id' => $user['id'],
                'firstname' => ucfirst(strtolower($user['firstname'])), // Formater le prénom
                'lastname' => ucfirst(strtolower($user['lastname'])),   // Formater le nom
                'bio' => $bio,
                'role' => $user['role'],
                'posts' => $posts,
                'website_link' => $website_link,
                'avatar' => $avatar,
                'skills' => $skills,
                'iat' => time(),                // Issued at : timestamp actuel
                'exp' => time() + 3600          // Expiration : une heure après la génération
            ];

            $jwt = \App\core\services\JwtService::generateToken($payload); // Génération du JWT

            // Étape 4 : Définir un cookie sécurisé avec le token JWT
            setcookie(
                "access_token",
                $jwt,
                [
                    "expires" => time() + 3600,      // Expiration dans une heure
                    "path" => "/",
                    "secure" => true,               // Accès sécurisé via HTTPS uniquement
                    "httponly" => true,             // Non accessible via JavaScript
                    "samesite" => "Strict"          // Protection contre les attaques CSRF
                ]
            );

            // Étape 5 : Redirection après réussite de l'authentification
            $_SESSION["connected"] = true; // Définir la session comme "connecté"
            header('Location: /');         // Redirection vers la page principale
            exit;

        } catch (\Exception $e) {
            // Gestion des exceptions et affichage d'un message d'erreur générique
            $_SESSION["errors"]["exception"] = "Erreur interne : " . $e->getMessage();
            header('Location: /login'); // Erreur 500 : Erreur serveur
            exit;
        }
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     * Supprime la session et les cookies, et redirige l'utilisateur vers la page principale.
     */
    public function logout()
    {
        session_start(); // Démarrage de la session pour garantir sa destruction
        session_destroy(); // Destruction de la session
        setcookie("access_token", "", time() - 3600, "/", "", false, true); // Suppression du cookie JWT
        header('Location: /'); // Redirection vers la page principale après déconnexion
        exit;
    }

    /**
     * Régénère un token JWT avec des données utilisateur mises à jour.
     * Utilisé par exemple après la mise à jour du profil d'un utilisateur.
     *
     * @param array $updatedUserData Données utilisateur mises à jour.
     */
    public function regenerateToken($updatedUserData)
    {
        $authModel = new \App\Models\UserModel(); // Instanciation du modèle utilisateur
        $postModel = new \App\Models\PostModel();
        // Vérification que le cookie JWT existe déjà
        if (isset($_COOKIE['access_token'])) {
            // Récupération des compétences utilisateur
            $userModel = new \App\Models\UserModel();
            $skills = $updatedUserData['skills'] ?? $userModel->findSkillsUserByID($updatedUserData['id']);

            // Étape 2 : Récupération du profil utilisateur (avatar, bio, site web)
            $profile = $authModel->findProfileByUserId($updatedUserData['id']);

            // Définir des valeurs par défaut si l'utilisateur n'a pas renseigné certaines informations
            $avatar = $profile['avatar'] ?? '';

            // Récupération des publications associées à l'utilisateur
            $posts = $postModel->findPostsUserByID($updatedUserData['id']);
            if (is_array($posts)) {
                foreach ($posts as &$post) {
                    $post = (array)$post; // Convertir chaque publication en tableau
                }
            }

            // Préparation du nouveau payload pour le JWT
            $newPayload = [
                'email' => $updatedUserData['email'],
                'id' => $updatedUserData['id'],
                'firstname' => ucfirst(strtolower($updatedUserData['firstname'])), // Formater le prénom
                'lastname' => ucfirst(strtolower($updatedUserData['lastname'])),   // Formater le nom
                'bio' => $updatedUserData['bio'] ?? 'Pas de biographie renseignée.',
                'role' => $updatedUserData['role'],
                'posts' => $posts,
                'website_link' => $updatedUserData['website_link'] ?? '',
                'avatar' => $avatar,
                'skills' => $skills,
                'iat' => time(),
                'exp' => time() + 3600
            ];

            // Génération du nouveau JWT
            $newJwt = \App\core\services\JwtService::generateToken($newPayload);

            // Mise à jour du cookie avec le nouveau token
            setcookie(
                "access_token",
                $newJwt,
                [
                    "expires" => time() + 3600,
                    "path" => "/",
                    "secure" => true,
                    "httponly" => true,
                    "samesite" => "Strict"
                ]
            );
        }
    }
}