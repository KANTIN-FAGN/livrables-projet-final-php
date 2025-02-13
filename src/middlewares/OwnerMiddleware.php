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
        // Logger pour débuter la gestion du middleware
        error_log("OwnerMiddleware : début.");

        // Vérification de la présence d'un token dans les cookies
        $token = $_COOKIE['access_token'] ?? null;

        if (!$token) {
            // Si aucun token n'est trouvé, retour à la page de login
            error_log("OwnerMiddleware : Aucun token trouvé dans les cookies.");
            http_response_code(401); // Code de réponse HTTP 401 : Non autorisé
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script
        }

        // Si un token est trouvé, commencer la vérification
        error_log("OwnerMiddleware : Token trouvé, vérification du JWT en cours.");
        try {
            // Vérifier et décoder le token en utilisant le service JwtService
            $decodedToken = JwtService::verifyToken($token);

            if (!$decodedToken) {
                // Si le token est invalide ou expiré, rediriger l'utilisateur
                error_log("OwnerMiddleware : Token JWT invalide ou expiré.");
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
                // Si l'ID est absent dans le token, redirection vers la page de login
                error_log("OwnerMiddleware : Échec. L'ID utilisateur est manquant dans le token.");
                http_response_code(401); // Non autorisé
                header('Location: /login'); // Redirection
                exit(); // Arrêt du script
            }
        } catch (\Exception $e) {
            // Gérer les exceptions potentielles lors de la vérification du token
            error_log("OwnerMiddleware : Erreur lors de la vérification du token - " . $e->getMessage());
            http_response_code(500); // Erreur interne du serveur
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script
        }
    }
}