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