<?php

namespace App\controllers;

use App\core\services\JwtService;

class AuthController
{
    public function login()
    {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            $_SESSION["errors"]["validation"] = "Email ou mot de passe manquant.";
            header('Location: ../views/auth/login/login.php', true, 400);
            exit;
        }

        try {
            $authModel = new \App\Models\UserModel();

            // Étape 1 : Récupérer l'utilisateur avec seulement ses informations principales
            $user = $authModel->findUserByEmail($email); // Pas d'avatar ici

            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION["errors"]["validation"] = "Email ou mot de passe incorrect.";
                header('Location: ../views/auth/login/login.php', true, 401);
                exit;
            }

            // Étape 2 : Récupérer l'avatar en fonction de l'ID utilisateur
            $profile = $authModel->findProfileByUserId($user['id']);

            // Ajouter une image par défaut si l'utilisateur n'a pas encore d'avatar
            $avatar = $profile['avatar'] ?? '/path/to/default/avatar.png';
            $bio = $profile['bio'] ?? 'Pas de biographie renseignée.';

            // Étape 3 : Générer le token JWT avec les informations utilisateur et l'avatar
            $payload = [
                'email' => $user['email'],
                'id' => $user['id'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'bio' => $bio,
                'role' => $user['role'],
                'avatar' => $avatar,
                'iat' => time(),
                'exp' => time() + 3600
            ];
            $jwt = \App\core\services\JwtService::generateToken($payload);

            // Étape 4 : Définir un cookie sécurisé
            setcookie(
                "access_token",
                $jwt,
                [
                    "expires" => time() + 3600, // Expire dans une heure
                    "path" => "/",
                    "secure" => true,           // Accessible uniquement en HTTPS
                    "httponly" => true,         // Non accessible via JavaScript
                    "samesite" => "Strict"      // CSRF protection
                ]
            );

            // Étape 5 : Rediriger vers une page sécurisée
            $_SESSION["connected"] = true;
            header('Location: /');
            exit;

        } catch (\Exception $e) {
            $_SESSION["errors"]["exception"] = "Erreur interne : " . $e->getMessage();
            header('Location: ../views/auth/login/login.php', true, 500);
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        setcookie("access_token", "", time() - 3600, "/", "", false, true);
        header('Location: /');
        exit;
    }
}