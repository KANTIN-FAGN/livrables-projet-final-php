<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    private $userModel;

    /**
     * Initialisation du contrôleur utilisateur avec une instance du modèle utilisateur
     */
    public function __construct()
    {
        // Instancie le modèle utilisateur pour l'interaction avec la base de données
        $this->userModel = new UserModel();
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     *
     * @param string $firstname Prénom de l'utilisateur
     * @param string $lastname Nom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     * @return int|bool Retourne l'ID de l'utilisateur créé ou false en cas d'échec
     */
    public function createUser($firstname, $lastname, $email, $password)
    {
        // Utilise le modèle pour insérer un nouvel utilisateur dans la base de données
        $userId = $this->userModel->createUser($firstname, $lastname, $email, $password);

        if ($userId) {
            // Crée un profil par défaut pour cet utilisateur
            $this->userModel->createDefaultProfile((int)$userId);
        }

        // Retourne l'ID de l'utilisateur créé
        return $userId;
    }

    /**
     * Vérifie si un utilisateur existe déjà dans la base de données via son email
     *
     * @param string $email Email de l'utilisateur à vérifier
     * @return bool Retourne true si l'utilisateur existe, sinon false
     */
    public function userExists($email): bool
    {
        // Exécute une requête via le modèle pour rechercher l'utilisateur par email
        $user =  $this->userModel->findUserByEmail($email);

        // Retourne true si un utilisateur est trouvé, sinon false
        return $user !== false;
    }

    /**
     * Récupère les compétences et niveaux d'un utilisateur
     *
     * @param int $id Identifiant de l'utilisateur
     * @return array|bool Retourne un tableau des compétences de l'utilisateur ou false en cas d'échec
     */
    public function getSkillsUser($id)
    {
        // Utilise le modèle pour récupérer les compétences de l'utilisateur par son ID
        $user = $this->userModel->findSkillsUserByID($id);

        // Retourne les compétences ou false si aucune compétence n'est trouvée
        return $user;
    }

    /**
     * Met à jour les informations générales de l'utilisateur dans la base de données
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $data Données à mettre à jour (assoc tableau)
     * @return int Nombre de lignes affectées
     * @throws \Exception Si l'ID ou les données sont manquants ou invalides
     */
    public function updateUser($id, $data): int
    {
        if (empty($id) || empty($data)) {
            throw new \Exception("Les données ou l'identifiant utilisateur sont invalides.");
        }

        $result = $this->userModel->updateUser((int)$id, $data);
        return is_numeric($result) ? intval($result) : 0; // Garantir un retour int
    }

    /**
     * Met à jour les informations du profil utilisateur
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $data Données à mettre à jour
     * @return array|bool Retourne les données mises à jour en cas de succès, ou false en cas d'échec
     */
    public function updateProfile($id, $data)
    {
        // Vérifier si le profil existe
        if (!$this->userModel->profileExists($id)) {
            // Si le profil n'existe pas, créer un profil par défaut
            $this->userModel->createDefaultProfile($id);
        }

        // Maintenant, vous pouvez mettre à jour le profil comme d'habitude
        $updatedData = $this->userModel->updateProfile($id, $data);

        if ($updatedData === false) {
            return "Aucune mise à jour effectuée pour le profil de l'utilisateur $id.";
        }

        return "Les données de l'utilisateur ont été mises à jour avec succès : " . json_encode($updatedData);
    }

    /**
     * Vérifie si le profil d'un utilisateur existe dans la base de données.
     *
     * Cette méthode utilise la méthode correspondante dans le modèle `UserModel`
     * pour déterminer si un profil est associé à un utilisateur selon son ID.
     *
     * @param int $userId L'ID de l'utilisateur à vérifier.
     * @return bool Retourne true si le profil existe, false sinon.
     */
    public function profileExists(int $userId): bool
    {
        // Appel de la méthode profileExists du modèle UserModel
        return $this->userModel->profileExists($userId);
    }

    /**
     * Crée un profil par défaut pour un utilisateur.
     *
     * Cette méthode délègue la création d'un profil à la méthode correspondante
     * dans le modèle `UserModel`. Le profil est créé avec des valeurs par défaut
     * (par exemple, un avatar, une bio ou un lien vide).
     *
     * @param int $userId L'ID de l'utilisateur pour lequel un profil doit être créé.
     * @return bool Retourne true si le profil a été créé avec succès, false sinon.
     */
    public function createDefaultProfile(int $userId): bool
    {
        // Appel de la méthode createDefaultProfile du modèle UserModel
        return $this->userModel->createDefaultProfile($userId);
    }

    /**
     * Met à jour les compétences d'un utilisateur en les supprimant puis en les réinsérant
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $skills Liste des compétences (IDs)
     * @param array $levels Liste des niveaux correspondants
     * @return int Nombre total de lignes affectées
     */
    public function updateUserSkills($id, $skills, $levels): int
    {
        if (empty($skills) || empty($levels)) {
            return 0; // Aucun changement
        }

        $result = $this->userModel->updateUserSkills($id, $skills, $levels);
        return is_numeric($result) ? intval($result) : 0;
    }

    /**
     * Récupère les informations complètes d'un utilisateur par son ID
     *
     * @param int $id Identifiant de l'utilisateur
     * @return array|bool Tableau des données utilisateur ou false si l'utilisateur n'existe pas
     */
    public function getUser($id)
    {
        // Appel au modèle pour effectuer une requête utilisateur via l'ID
        $user = $this->userModel->findUserByID($id);

        // Retourne les données utilisateur
        return $user;
    }
}