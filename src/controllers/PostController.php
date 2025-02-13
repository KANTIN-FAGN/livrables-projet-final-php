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
        $result = $this->postModel->findPostByID($id);
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

}