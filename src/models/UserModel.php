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
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            error_log('data = ' . print_r($result, true));

            // Retourner null si aucun résultat n'est trouvé
            return $result !== false ? $result : null;
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            return null; // Retourner null en cas d'erreur
        }
    }

    /**
     * Récupère tous les posts (publications) associés à un utilisateur donné, à partir de son identifiant.
     *
     * @param int $userId L'identifiant unique de l'utilisateur
     * @return array Un tableau contenant les publications de l'utilisateur, ou un tableau vide en cas d'erreur ou d'absence de publications
     */
    public function findPostsUserByID(int $userId): array
    {
        // Requête SQL pour sélectionner toutes les colonnes dans la table "posts" où "user_id" correspond à l'utilisateur donné
        $query = "SELECT * FROM posts WHERE user_id = :user_id"; // Suppose que la table s'appelle "posts"

        try {
            // Préparation de la requête SQL avec le PDO (permet de sécuriser la requête en évitant les injections SQL)
            $statement = $this->pdo->prepare($query);

            // Liaison du paramètre ":user_id" avec la valeur de $userId (de type entier)
            $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Exécution de la requête
            $statement->execute();

            // Récupération de tous les résultats sous forme d'un tableau associatif
            // Si aucun résultat n'est trouvé, un tableau vide sera retourné
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // En cas d'erreur, on journalise un message pour tracer l'erreur
            error_log("Erreur dans findPostsUserByID : " . $e->getMessage());

            // Retourne un tableau vide pour signaler l'absence de données en cas d'échec
            return [];
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
     * Met à jour les informations générales d'un utilisateur dans la table 'users'
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $data Données à mettre à jour (clé => valeur)
     * @return int Retourne le nombre de lignes affectées
     */
    public function updateUser(int $id, array $data): int
    {
        if (empty($data)) {
            return 0; // Aucun changement
        }

        try {
            $setClause = [];
            foreach ($data as $column => $value) {
                $setClause[] = "$column = :$column";
            }

            $query = "UPDATE users SET " . implode(', ', $setClause) . " WHERE id = :id";
            $statement = $this->pdo->prepare($query);

            foreach ($data as $column => $value) {
                $statement->bindValue(":$column", $value);
            }
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->rowCount(); // Retourner strictement un int
        } catch (\Exception $e) {
            error_log("Erreur dans updateUser : " . $e->getMessage());
            return 0; // En cas d'erreur, toujours retourner 0
        }
    }

    /**
     * Met à jour le profil utilisateur (ex. bio, site web) dans la table 'profiles'
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $data Données à mettre à jour (clé => valeur)
     * @return int Retourne le nombre de lignes affectées
     */
    public function updateProfile(int $id, array $data): int
    {
        if (empty($data)) {
            return 0; // Aucun changement
        }

        try {
            $setClause = [];
            foreach ($data as $column => $value) {
                $setClause[] = "$column = :$column";
            }

            $query = "UPDATE profiles SET " . implode(', ', $setClause) . " WHERE user_id = :id";
            $statement = $this->pdo->prepare($query);

            foreach ($data as $column => $value) {
                $statement->bindValue(":$column", $value);
            }
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->rowCount(); // Retourner strictement un int
        } catch (\Exception $e) {
            error_log("Erreur dans updateProfile : " . $e->getMessage());
            return 0; // Toujours retour 0 en cas de problème
        }
    }


    public function updateAvatar(int $id, string $avatarPath): bool
    {
        $sql = "UPDATE profiles SET avatar = :avatar WHERE user_id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':avatar', $avatarPath, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        return $statement->execute();
    }

    /**
     * Crée un profil par défaut pour un utilisateur.
     * Insère un nouvel enregistrement dans la table `profiles` avec des valeurs par défaut.
     *
     * @param int $userId L'ID de l'utilisateur pour lequel créer le profil.
     * @return bool Retourne true si le profil a été créé avec succès, false sinon.
     */
    public function createDefaultProfile(int $userId): bool
    {
        // Requête SQL pour insérer un profil par défaut lié à l'utilisateur
        $sql = "INSERT INTO profiles (user_id, avatar, bio, website_link) VALUES (:userId, '', '', '')";
        try {
            $statement = $this->pdo->prepare($sql); // Préparation de la requête
            $statement->bindParam(":userId", $userId, PDO::PARAM_INT); // Association du paramètre :userId
            return $statement->execute(); // Exécution de la requête
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs pour diagnostic
            error_log("Erreur lors de la création du profil : " . $e->getMessage());
            return false; // Retourner false en cas d'échec
        }
    }

    /**
     * Vérifie si un profil existe pour un utilisateur donné.
     * Recherche dans la table `profiles` si un enregistrement contenant l'ID utilisateur existe.
     *
     * @param int $userId L'ID de l'utilisateur pour lequel vérifier le profil.
     * @return bool Retourne true si un profil existe, false sinon.
     */
    public function profileExists(int $userId): bool
    {
        // Requête SQL pour compter le nombre de profils associés à un utilisateur
        $sql = "SELECT COUNT(*) FROM profiles WHERE user_id = :userId";
        $statement = $this->pdo->prepare($sql); // Préparation de la requête
        $statement->bindParam(":userId", $userId, PDO::PARAM_INT); // Association du paramètre :userId
        $statement->execute(); // Exécution de la requête
        return $statement->fetchColumn() > 0; // Retourne true si un ou plusieurs profils existent
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