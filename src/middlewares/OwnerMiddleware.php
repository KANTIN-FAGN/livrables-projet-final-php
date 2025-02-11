<?php
namespace middlewares;

use App\core\services\JwtService;

class OwnerMiddleware
{
    public function handle()
    {
        error_log("OwnerMiddleware : début.");

        // Vérification du cookie
        $token = $_COOKIE['access_token'] ?? null;

        if (!$token) {
            error_log("OwnerMiddleware : Aucun token trouvé dans les cookies.");
            http_response_code(401);
            echo "Accès non autorisé : Token manquant.";
            exit();
        }

        // Vérification et décodage du token
        error_log("OwnerMiddleware : Token trouvé, vérification du JWT en cours.");
        try {
            $decodedToken = JwtService::verifyToken($token);

            if (!$decodedToken) {
                error_log("OwnerMiddleware : Token JWT invalide ou expiré.");
                http_response_code(401);
                echo "Accès non autorisé : Token invalide.";
                exit();
            }

            // Vérification de l'ID utilisateur
            $_SESSION['id'] = $decodedToken['id'] ?? null;

            if ($_SESSION['id']) {
                error_log("OwnerMiddleware : Succès. Utilisateur identifié, ID=" . $_SESSION['id']);
            } else {
                error_log("OwnerMiddleware : Échec. L'ID utilisateur est manquant dans le token.");
                http_response_code(401);
                echo "Accès non autorisé : Impossible d'identifier l'utilisateur.";
                exit();
            }
        } catch (\Exception $e) {
            error_log("OwnerMiddleware : Erreur lors de la vérification du token - " . $e->getMessage());
            http_response_code(500);
            echo "Erreur interne (500) : Problème lors de la validation du token.";
            exit();
        }
    }
}