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
     * @param array $data Données à mettre à jour (assoc array)
     * @return int Nombre de lignes affectées
     * @throws \Exception Si l'ID ou les données sont manquants ou invalides
     */
    public function updateUser($id, $data): int
    {
        // Validation : Vérifie si l'ID et les données à mettre à jour sont non vides
        if (empty($id) || empty($data)) {
            throw new \Exception("Les données ou l'identifiant utilisateur sont invalides.");
        }

        // Journalisation des données pour suivre les mises à jour
        error_log("Mise à jour utilisateur pour ID=$id avec données : " . json_encode($data));

        // Appel au modèle pour effectuer la mise à jour et récupérer les lignes affectées
        $affectedRows = $this->userModel->updateUser((int)$id, $data);

        // S'assurer que le retour est un entier, renvoyer 0 sinon
        return is_int($affectedRows) ? $affectedRows : 0;
    }

    /**
     * Met à jour la biographie, le site web et d'autres données du profil utilisateur
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $data Données à mettre à jour (biographie, site web, autres champs)
     * @return int Nombre total de lignes affectées
     */
    public function updateProfile($id, $data): int
    {
        // Variable pour suivre le nombre de lignes affectées
        $rowsUpdated = 0;

        // Gestion spécifique de la biographie et du site web
        if (isset($data['bio'])) {
            // Extraire et supprimer la biographie des données
            $bio = $data['bio'];
            unset($data['bio']);

            // Extraire et supprimer l'URL du site web
            $website_link = $data['website'];
            unset($data['website']);

            // Met à jour la biographie dans la base de données
            $bioUpdated = $this->userModel->updateProfile($id, ['bio' => $bio]);
            if (is_int($bioUpdated)) {
                $rowsUpdated += $bioUpdated;
            }

            // Met à jour l'URL du site web dans la base de données
            $websiteUpdated = $this->userModel->updateProfile($id, ['website_link' => $website_link]);
            if (is_int($websiteUpdated)) {
                $rowsUpdated += $websiteUpdated;
            }

            // Journalisation de la mise à jour
            error_log("Mise à jour de la biographie et du site web pour utilisateur ID=$id");
        }

        // Gestion d'autres champs restants dans les données, le cas échéant
        if (!empty($data)) {
            $otherUpdated = $this->userModel->updateUser($id, $data);
            if (is_int($otherUpdated)) {
                $rowsUpdated += $otherUpdated;
            }
        }

        // Retourne le nombre total de lignes mises à jour
        return $rowsUpdated;
    }

    /**
     * Met à jour les compétences d'un utilisateur en les supprimant puis en les réinsérant
     *
     * @param int $id Identifiant de l'utilisateur
     * @param array $skills Liste des compétences (IDs)
     * @param array $levels Liste des niveaux correspondants
     * @return int Nombre total de lignes affectées
     */
    public function updateUserSkills($id, $skills, $levels)
    {
        // Appel au modèle pour mettre à jour les compétences, avec suppression/reinsertion
        return $this->userModel->updateUserSkills((int)$id, $skills, $levels);
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