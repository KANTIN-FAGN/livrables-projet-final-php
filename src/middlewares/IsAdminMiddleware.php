<?php

namespace middlewares;

use App\core\services\JwtService;

class IsAdminMiddleware
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
        $token = $_COOKIE['access_token'] ?? null; // Récupération du token depuis les cookies

        if (!$token) {
            // Si aucun token n'est trouvé, on retourne un code 401 et on redirige vers la page de connexion
            http_response_code(401); // Code de réponse HTTP 401 : Non autorisé
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script pour empêcher l'exécution ultérieure
        }

        try {
            // Vérifier et décoder le token en utilisant le service JwtService
            $decodedToken = JwtService::verifyToken($token); // Vérification et décodage du token JWT

            if (!$decodedToken) {
                // Si le token n'est pas décodé correctement (invalidité ou altération), on bloque l'accès
                http_response_code(401); // Non autorisé
                header('Location: /login'); // Redirection vers la page de login
                exit(); // Arrêt du script
            }

            // Extraction du rôle à partir du token décodé
            $_SESSION['role'] = $decodedToken['role'] ?? null; // Ajout du rôle extrait au tableau de session

            if ($_SESSION['role'] === 'admin') {
                // Si l'utilisateur possède le rôle admin
                error_log("IsAdminMiddleware : Succès. Utilisateur a le rôle admin.");
            } else {
                // Si l'utilisateur n'a pas le rôle admin, on bloque également l'accès
                http_response_code(401); // Non autorisé
                header('Location: /'); // Redirection vers la page principale
                exit(); // Arrêt du script
            }
        } catch (\Exception $e) {
            // Gestion des erreurs lors de la validation ou du traitement du token
            http_response_code(500); // Code HTTP d'erreur interne du serveur
            header('Location: /login'); // Redirection vers la page de login
            exit(); // Arrêt du script
        }
    }
}