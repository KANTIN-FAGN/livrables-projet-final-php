<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $password
     * @return bool|int
     */
    public function createUser($firstname, $lastname, $email, $password)
    {
        // Utilisation du modèle pour créer l'utilisateur
        $userId = $this->userModel->createUser($firstname, $lastname, $email, $password);

        // Retour de l'ID de l'utilisateur après création
        return $userId;
    }

    /**
     * @param $email
     * @return bool
     */
    public function userExists($email): bool
    {
        $user =  $this->userModel->findUserByEmail($email);
        return $user !== false;
    }

    public function getSkillsUser($id) {
        $user = $this->userModel->findSkillsUserByID($id);
        return $user;
    }

    public function updateUser($id, $data): int
    {
        if (empty($id) || empty($data)) {
            throw new \Exception("Les données ou l'identifiant utilisateur sont invalides.");
        }

        error_log("Mise à jour utilisateur pour ID=$id avec données : " . json_encode($data));

        // Met à jour les données et retourne directement le nombre de lignes affectées
        $affectedRows = $this->userModel->updateUser((int)$id, $data);
        return is_int($affectedRows) ? $affectedRows : 0; // Toujours un entier
    }

    public function updateProfile($id, $data): int
    {
        $rowsUpdated = 0;

        if (isset($data['bio'])) {
            $bio = $data['bio'];
            unset($data['bio']);
            $website_link = $data['website'];
            unset($data['website']);

            // Met à jour la biographie et ajoute au compteur
            $bioUpdated = $this->userModel->updateProfile($id, ['bio' => $bio]);
            if (is_int($bioUpdated)) {
                $rowsUpdated += $bioUpdated;
            }

            $websiteUpdated = $this->userModel->updateProfile($id, ['website_link' => $website_link]);
            if (is_int($websiteUpdated)) {
                $rowsUpdated += $websiteUpdated;
            }
            error_log("Mise à jour de la biographie et du site web pour utilisateur ID=$id");

        }

        if (!empty($data)) {
            // Met à jour les autres données et ajoute au compteur
            $otherUpdated = $this->userModel->updateUser($id, $data);
            if (is_int($otherUpdated)) {
                $rowsUpdated += $otherUpdated;
            }
        }

        return $rowsUpdated; // Retourne uniquement le nombre de lignes mises à jour
    }

    /**
     * @param $id
     * @return array|bool
     */
    public function getUser($id)
    {
        $user = $this->userModel->findUserByID($id);
        return $user;
    }
}