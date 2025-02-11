<?php

namespace App\config;

use Dotenv\Dotenv; // Utilisation de la bibliothèque Dotenv pour charger les variables d'environnement
use PDO; // Importation de classe PDO pour les connexions à la base de données

class Database
{
    // Instance unique (singleton) de la connexion PDO
    private static ?PDO $pdo = null;

    /**
     * Fournit une instance unique PDO pour interagir avec la base de données.
     * Implémente le pattern Singleton pour s'assurer qu'une seule connexion à la base est utilisée.
     *
     * @return PDO Retourne l'instance PDO
     */
    public static function getPDO(): PDO
    {
        // Vérifie si le fichier .env existe et charge les variables d'environnement
        if (file_exists(__DIR__ . '../../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Crée une instance Dotenv
            $dotenv->load(); // Charge les variables d'environnement dans `$_ENV`
        }

        // Si aucune connexion n'existe encore, crée une instance PDO
        if (self::$pdo === null) {
            // Configuration des paramètres de connexion par défaut
            $host = '127.0.0.1'; // Adresse de l'hôte (localhost par défaut)
            $dbname = 'projetb2'; // Nom de la base de données
            $port = 8889; // Port de connexion MySQL (8889 pour MAMP, sinon 3306 par défaut)
            $user = 'projetb2'; // Nom d'utilisateur de la base
            $password = 'password'; // Mot de passe de l'utilisateur

            try {
                // Création de la connexion PDO avec les paramètres fournis
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8", // Chaîne de connexion DSN
                    $user, // Utilisateur
                    $password, // Mot de passe
                    [
                        // Configuration des options PDO
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions pour les erreurs SQL
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Définit le mode de récupération par défaut en tableau associatif
                    ]
                );
            } catch (\Exception $e) {
                // Gestion des erreurs de connexion : arrêter l'exécution et afficher un message d'erreur
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        // Retourne l'instance PDO existante ou nouvellement créée
        return self::$pdo;
    }
}