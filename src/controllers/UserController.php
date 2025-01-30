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

    public function createUser($firstname, $lastname, $email, $password)
    {
        // Utilisation du modèle pour créer l'utilisateur
        $userId = $this->userModel->createUser($firstname, $lastname, $email, $password);

        // Retour de l'ID de l'utilisateur après création
        return $userId;
    }

    public function userExists($email): bool
    {
        $user =  $this->userModel->findUserByIEmail($email);

        return $user !== false;
    }

    public function getUser($id)
    {
        $user = $this->userModel->findUserByID($id);
        return $user;
    }
}