<?php

namespace App\core\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private static string $secretKey;
    private static string $algo = 'HS256';

    // Initialisation statique de la clé (par exemple, via un fichier .env)
    public static function initialize(): void
    {
        if (!isset(self::$secretKey)) {
            self::$secretKey = $_ENV['JWT_SECRET'] ?? 'your_default_secret_key'; // Fournir une clé par défaut si nécessaire
        }
    }

    // Générer un token
    public static function generateToken(array $payload): string
    {
        self::initialize();
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600; // Token valide pendant 1 heure

        // Ajoutez des champs nécessaires ici
        if (!isset($payload['id'])) {
            error_log("JwtService::generateToken - Avertissement : L'ID utilisateur n'est pas défini dans le payload.");
        }

        return JWT::encode($payload, self::$secretKey, self::$algo);
    }

    // Vérifier et décoder un token
    public static function verifyToken(string $jwt): ?array
    {
        self::initialize(); // S'assurer que la clé est initialisée
        try {
            $decoded = JWT::decode($jwt, new Key(self::$secretKey, self::$algo));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}