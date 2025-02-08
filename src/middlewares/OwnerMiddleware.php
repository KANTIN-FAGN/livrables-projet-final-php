<?php

namespace middlewares;

use App\core\services\JwtService;

class OwnerMiddleware
{
    public function handle()
    {
        // Récupérer le token JWT depuis les cookies
        $token = $_COOKIE['access_token'] ?? null;

        if (!$token) {
            // Aucun token fourni dans les cookies
            http_response_code(401);
            echo "Accès non autorisé. Token manquant.";
            exit();
        }

        // Décoder et vérifier le token JWT
        $decodedToken = JwtService::verifyToken($token);

        if (!$decodedToken) {
            http_response_code(401);
            echo "Accès non autorisé. Token invalide.";
            exit();
        }

        // Stocker l'ID de l'utilisateur pour une utilisation ultérieure
        $_SESSION['id'] = $decodedToken['id'] ?? null;

        if (!$_SESSION['id']) {
            http_response_code(401);
            echo "Accès non autorisé. Impossible d'identifier l'utilisateur.";
            exit();
        }

        // Si tout est valide, le middleware passe à l'étape suivante
    }
}