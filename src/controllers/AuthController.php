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
            // Simuler un utilisateur récupéré depuis la base de données
            $authModel = new \App\Models\UserModel();
            $user = $authModel->findUserByIEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION["errors"]["validation"] = "Email ou mot de passe incorrect.";
                header('Location: ../views/auth/login/login.php', true, 401);
                exit;
            }

            // Générer le token JWT
            $payload = [
                'email' => $user['email'],
                'id' => $user['id'],
                'iat' => time(),
                'exp' => time() + 3600 // Expire en 1 heure
            ];
            $jwt = \App\Core\Services\JwtService::generateToken($payload);

            // Définir un cookie sécurisé
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

            // Rediriger vers une page sécurisée (tableau de bord par exemple)
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
        // Supprimez le cookie JWT
        session_start();
        session_destroy();
        setcookie("access_token", "", time() - 3600, "/", "", false, true);
        echo "Déconnexion réussie.";
    }
}