<?php
namespace middlewares;

use App\core\services\JwtService;

class OwnerMiddleware
{
    /**
     * Méthode principale pour gérer le middleware.
     *
     * Vérifie la présence d'un token JWT dans les cookies, valide ce dernier,
     * et associe l'utilisateur à une session si le token est valide.
     */
    public function handle()
    {

        // Vérification de la présence d'un token dans les cookies
        $token = $_COOKIE['access_token'] ?? null;

        if (!$token) {
            http_response_code(401); // Code de réponse HTTP 401 : Non autorisé
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script
        }

        try {
            // Vérifier et décoder le token en utilisant le service JwtService
            $decodedToken = JwtService::verifyToken($token);

            if (!$decodedToken) {
                http_response_code(401); // Non autorisé
                header('Location: /login'); // Redirection
                exit(); // Arrêt du script
            }

            // Extraction de l'ID utilisateur à partir du token décodé
            $_SESSION['id'] = $decodedToken['id'] ?? null;

            if ($_SESSION['id']) {
                // Si l'ID utilisateur est présent, l'utilisateur est authentifié avec succès
                error_log("OwnerMiddleware : Succès. Utilisateur identifié, ID=" . $_SESSION['id']);
            } else {
                http_response_code(401); // Non autorisé
                header('Location: /login'); // Redirection
                exit(); // Arrêt du script
            }
        } catch (\Exception $e) {
            http_response_code(500); // Erreur interne du serveur
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script
        }
    }
}