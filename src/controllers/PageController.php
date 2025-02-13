<?php

namespace App\Controllers;

use App\controllers\PostController;

class PageController
{

    private $postController;

    /**
     * Initialisation du contrôleur utilisateur avec une instance du modèle utilisateur
     */
    public function __construct()
    {
        // Instancie le modèle utilisateur pour l'interaction avec la base de données
        $this->postController = new PostController();
    }

    public static function home()
    {
        include_once '../views/home/home.php';
    }

    /**
     * @return void
     */
    public static function register()
    {
        include_once '../views/auth/register/register.php';
    }

    /**
     * @return void
     */
    public static function registerService()
    {
        include_once '../views/auth/register/services/registerService.php';
    }

    /**
     * @return void
     */
    public static function login()
    {
        include_once '../views/auth/login/login.php';
    }

    /**
     * @return void
     */
    public static function loginService()
    {
        include_once '../views/auth/login/services/loginService.php';
    }

    public static function profile()
    {
        include_once '../views/profile/profile.php';
    }

    public static function editProfile()
    {
        include_once '../views/profile/services/profileService.php';
    }

    public static function createPost()
    {
        include_once '../views/profile/services/postService.php';
    }

    public static function editPost(int $id)
    {
        $postController = new PostController(); // Créer une instance ici
        $post = $postController->getPostById($id); // Appeler la méthode via l'instance

        error_log('data : ' . print_r($post, true));

        // Vérifiez si le post existe
        if (!$post) {
            die("Le post avec l'ID $id n'existe pas.");
        }

        // Passez les données du post dans le formulaire
        include_once '../views/profile/editPost/editPost.php';
    }

    public static function editPostService()
    {
        include_once '../views/profile/editPost/services/editPostService.php';
    }

    public static function deletePost(int $id)
    {

        error_log('id : ' . $id);

        $postController = new PostController(); // Créer une instance ici
        $post = $postController->getPostById($id); // Appeler la méthode via l'instance

        // Résultat loggué pour vérifier
        error_log('Résultat de getPostById : ' . print_r($post, true));

        // Vérifiez si le post existe
        if (!$post) {
            die("Le post avec l'ID $id n'existe pas.");
        }

        // Inclure le fichier correspondant au service de suppression
        include_once '../views/profile/services/postDeleteService.php';
    }
}