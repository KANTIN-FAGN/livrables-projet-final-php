<?php

namespace App\Models;

use App\config\Database;
use PDO;
use Exception;

class UserModel
{
    protected $table = 'users'; // Nom de la table des utilisateurs
    private $pdo = null; // Instance de la connexion à la base de données
    protected $keyName = 'id'; // Colonne clé primaire pour la table

    /**
     * Constructeur de la classe UserModel
     * Initialise la connexion à la base de données à l'aide du singleton Database
     */
    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     *
     * @param string $firstname Prénom de l'utilisateur
     * @param string $lastname Nom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur (idéalement hashé)
     * @return int|bool Retourne l'ID du nouvel utilisateur ou false en cas d'échec
     */
    public function createUser(string $firstname, string $lastname, string $email, string $password): int|bool
    {
        $sql = "INSERT INTO $this->table (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':email' => $email,
                ':password' => $password,
            ]);
            return $this->pdo->lastInsertId(); // Retourne l'ID du dernier enregistrement inséré
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs depuis la table 'users'
     *
     * @return array|bool Retourne un tableau associatif contenant les utilisateurs ou false en cas d'échec
     */
    public function findAllUsers(): array|bool
    {
        $sql = "SELECT * FROM $this->table";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les utilisateurs sous forme de tableau associatif
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Trouve un utilisateur par son ID
     *
     * @param string $id Identifiant de l'utilisateur
     * @return array|bool Tableau contenant les données de l'utilisateur ou false en cas d'échec
     */
    public function findUserByID(string $id): array|bool
    {
        $sql = "SELECT u.id, u.firstname, u.lastname, p.bio, p.website_link 
                FROM users u
                LEFT JOIN profiles p ON u.id = p.user_id
                WHERE u.id = :id";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $id); // Liaison paramétrée
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC); // Retourne un seul utilisateur
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Trouve un utilisateur par son email
     *
     * @param string $email Email de l'utilisateur
     * @return array|bool Tableau contenant les données de l'utilisateur ou false en cas d'échec
     */
    public function findUserByEmail(string $email): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":email", $email); // Liaison paramétrée
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Trouve le profil d'un utilisateur à partir de son ID
     *
     * @param int $userId ID de l'utilisateur
     * @return array|null Retourne les données du profil ou null si non trouvé
     */
    public function findProfileByUserId(int $userId): ?array
    {
        error_log("findProfileByUserId : Recherche du profil pour userId=$userId");
        $query = "SELECT * FROM profiles WHERE user_id = :user_id";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC) ?? null;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les compétences et niveaux d'un utilisateur
     *
     * @param int $id ID de l'utilisateur
     * @return array|bool Retourne un tableau contenant les compétences et niveaux de l'utilisateur, false en cas d'échec
     */
    public function findSkillsUserByID(int $id): array|bool
    {
        $query = "SELECT skills.name, user_skills.level
                  FROM user_skills
                  JOIN skills ON user_skills.skill_id = skills.id
                  WHERE user_skills.user_id = :user_id";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':user_id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Met à jour les informations d'un utilisateur
     *
     * @param int $id ID de l'utilisateur à mettre à jour
     * @param array $values Données à mettre à jour (tableau associatif)
     * @return int|bool Retourne le nombre de lignes affectées ou false en cas d'échec
     */
    public function updateUser(int $id, array $values): int|bool
    {
        if (empty($values)) { // Validation
            return false;
        }

        // Construire la requête SQL dynamique "UPDATE"
        $sql = "UPDATE $this->table SET ";
        $columns = [];
        foreach ($values as $key => $value) {
            $columns[] = "$key = :$key";
        }
        $sql .= implode(', ', $columns) . " WHERE $this->keyName = :id";

        try {
            $statement = $this->pdo->prepare($sql);

            // Assignation des valeurs
            foreach ($values as $key => $val) {
                $statement->bindParam(":$key", $val);
            }
            $statement->bindParam(":id", $id, PDO::PARAM_INT);

            $statement->execute();
            return $statement->rowCount(); // Retourne le nombre de lignes affectées
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Met à jour un profil utilisateur à partir de son ID
     *
     * @param int $userId ID de l'utilisateur
     * @param array $values Données du profil à mettre à jour
     * @return array|bool Retourne les nouvelles données du profil ou false en cas d'échec
     */
    public function updateProfile(int $userId, array $values): array|bool
    {
        if (empty($values)) {
            return false;
        }

        // Génération de la requête SQL dynamique
        $sql = "UPDATE profiles SET ";
        $columns = [];
        foreach ($values as $key => $value) {
            $columns[] = "$key = :$key";
        }
        $sql .= implode(', ', $columns) . " WHERE user_id = :user_id";

        try {
            $statement = $this->pdo->prepare($sql);

            // Liaison des paramètres
            foreach ($values as $key => $val) {
                $statement->bindParam(":$key", $val);
            }
            $statement->bindParam(":user_id", $userId, PDO::PARAM_INT);

            $statement->execute();
            return $this->findProfileByUserId($userId); // Retourne les nouvelles données
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Met à jour les compétences d'un utilisateur.
     *
     * @param int $userId ID de l'utilisateur
     * @param array $skills Tableau des IDs des compétences
     * @param array $levels Tableau des niveaux associés aux compétences
     * @return bool Retourne true si la mise à jour réussit, false en cas d'erreur
     * @throws Exception Si les tailles des tableaux de compétences et niveaux ne correspondent pas
     */
    public function updateUserSkills(int $userId, array $skills, array $levels): bool
    {
        // Vérifier que les tableaux $skills et $levels possèdent le même nombre d'éléments
        if (count($skills) !== count($levels)) {
            throw new Exception("Le nombre de compétences et de niveaux ne correspond pas.");
        }

        try {
            // Démarrer une transaction pour assurer l'intégrité des données
            $this->pdo->beginTransaction();

            // Étape 1 : Supprimer toutes les compétences actuelles de l'utilisateur
            $deleteSql = "DELETE FROM user_skills WHERE user_id = :user_id"; // Requête SQL de suppression
            $deleteStmt = $this->pdo->prepare($deleteSql); // Préparation de la requête
            $deleteStmt->execute([':user_id' => $userId]); // Exécution de la requête en liant l'ID utilisateur

            // Étape 2 : Insérer les nouvelles compétences et niveaux
            $insertSql = "INSERT INTO user_skills (user_id, skill_id, level) VALUES (:user_id, :skill_id, :level)";
            $insertStmt = $this->pdo->prepare($insertSql); // Préparation de la requête d'insertion

            // Boucle pour insérer chaque compétence et son niveau
            foreach ($skills as $index => $skillId) {
                $insertStmt->execute([
                    ':user_id' => $userId,          // ID de l'utilisateur
                    ':skill_id' => $skillId,       // ID de la compétence
                    ':level' => $levels[$index],   // Niveau correspondant
                ]);
            }

            // Valider la transaction si toutes les opérations réussissent
            $this->pdo->commit();

            return true; // Renvoie true en cas de succès
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur pour éviter des données incohérentes
            $this->pdo->rollBack();
            echo "Erreur : " . $e->getMessage(); // Afficher un message d'erreur (à remplacer par une gestion plus propre en prod)
            return false; // Renvoie false en cas d'échec
        }
    }
}