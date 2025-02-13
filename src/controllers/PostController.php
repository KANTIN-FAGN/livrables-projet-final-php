<?php

namespace App\controllers;

use App\Models\PostModel;

class PostController
{
    private $postModel;

    /**
     * Constructeur de PostController.
     * Initialise une instance de PostModel pour interagir avec la base de données.
     */
    public function __construct()
    {
        // Instancie PostModel pour gérer les opérations liées aux posts
        $this->postModel = new PostModel();
    }

    /**
     * Récupère tous les posts.
     * Peut être développé pour retourner une liste de posts depuis la base de données.
     */
    public function getPosts()
    {
        $result = $this->postModel->getPosts();
        return $result;
    }

    /**
     * Récupère les détails d'un post via son ID.
     *
     * @param int|string $id L'identifiant du post.
     * @return array|null Les informations du post sous forme de tableau ou null si le post n'est pas trouvé.
     */
    public function getPostById($id)
    {
        // Appel de la méthode du modèle pour obtenir un post par ID
        $result = $this->postModel->getPostById($id);

        // Retourne les données du post ou null si non trouvé
        return $result;
    }

    /**
     * Crée un nouveau post avec les informations fournies.
     *
     * @param int $user_id ID de l'utilisateur créant le post.
     * @param string $title Titre du post.
     * @param string $description Description du post.
     * @param string $image_path Chemin vers l'image associée.
     * @param string $external_link Lien externe lié au post.
     * @return array Résultat indiquant le statut de la création.
     */
    public function createPost(int $user_id, string $title, string $description, string $image_path, string $external_link)
    {
        // Appel du modèle pour insérer un nouveau post dans la base de données
        $result = $this->postModel->createPost($user_id, $title, $description, $image_path, $external_link);

        // Vérifie si la création a réussi
        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Post créé avec succès.',
                'post_id' => $result, // Retourne l'ID du post nouvellement créé
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Erreur lors de la création du post.', // Message d'erreur en cas d'échec
            ];
        }
    }

    /**
     * Met à jour les informations d'un post existant.
     *
     * @param int $id ID du post à mettre à jour.
     * @param string $title Nouveau titre du post.
     * @param string $description Nouvelle description du post.
     * @param string $image_path Nouveau chemin d'image du post.
     * @param string $external_link Nouveau lien externe lié au post.
     * @return bool True si la mise à jour a réussi, False sinon.
     */
    public function updatePost(int $id, string $title, string $description, string $image_path, string $external_link)
    {
        // Préparation des données mises à jour
        $data = [
            'title' => $title,
            'description' => $description,
            'image_path' => $image_path,
            'external_link' => $external_link,
        ];

        // Appel de la méthode `updatePost` du modèle pour mettre à jour le post
        $result = $this->postModel->updatePost($id, $data);

        // Journalisation et retour selon le résultat
        if ($result) {
            error_log("Mise à jour réussie du post avec l'ID $id");
            return true;
        } else {
            error_log("Échec de la mise à jour du post avec l'ID $id");
            return false;
        }
    }

    /**
     * Récupère l'image d'un post via son ID.
     *
     * @param int $id ID du post.
     * @return string|null Chemin vers l'image du post ou null si non trouvé.
     */
    public function getImagePostByID(int $id)
    {
        // Appel de la méthode du modèle pour obtenir l'image d'un post
        $result = $this->postModel->findImagePostByID($id);

        // Retourne le résultat
        return $result;
    }

    /**
     * Supprime un post via son ID.
     *
     * @param int $id ID du post à supprimer.
     * @return bool True si la suppression a réussi, False sinon.
     */
    public function deletePostById(int $id)
    {
        // Appel de la méthode du modèle pour supprimer un post
        $result = $this->postModel->deletePost($id);

        // Retourne le résultat (true ou false)
        return $result;
    }
}