<?php

namespace App\controllers;

use App\Models\PostModel;

class PostController
{
    private $postModel;

    /**
     * Initialisation du contrôleur utilisateur avec une instance du modèle utilisateur
     */
    public function __construct()
    {
        // Instancie le modèle utilisateur pour l'interaction avec la base de données
        $this->postModel = new PostModel();
    }

    public function getPosts()
    {
    }

    public function getPostById($id)
    {
        $result = $this->postModel->getPostById($id);
        return $result;
    }

    public function createPost(int $user_id, string $title, string $description, string $image_path, string $external_link)
    {
        $result = $this->postModel->createPost($user_id, $title, $description, $image_path, $external_link);

        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Post créé avec succès.',
                'post_id' => $result, // ID de l'enregistrement créé
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Erreur lors de la création du post.',
            ];
        }
    }

    public function updatePost(int $id, string $title, string $description, string $image_path, string $external_link)
    {
        // Préparation des données pour la mise à jour
        $data = [
            'title' => $title,
            'description' => $description,
            'image_path' => $image_path,
            'external_link' => $external_link,
        ];

        // Appel de la méthode `updatePost` du modèle
        $result = $this->postModel->updatePost($id, $data);

        // Vérifiez le résultat pour déterminer si la mise à jour a été réussie
        if ($result) {
            error_log("Mise à jour réussie du post avec l'ID $id");
            return true;
        } else {
            error_log("Échec de la mise à jour du post avec l'ID $id");
            return false;
        }
    }

    public function getImagePostByID(int $id)
    {
        $result = $this->postModel->findImagePostByID($id);
        return $result;
    }

    public function deletePostById(int $id)
    {
        $result = $this->postModel->deletePost($id);
        return $result;
    }
}