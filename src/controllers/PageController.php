<?php

namespace App\Controllers;

use App\controllers\PostController;

class PageController
{

    private $postController;

    /**
     * Constructeur du contrôleur PageController.
     * Initialisation d'une instance du PostController pour gérer les posts.
     */
    public function __construct()
    {
        // Instancie le PostController pour l'interaction avec la base de données des posts
        $this->postController = new PostController();
    }

    /**
     * Méthode pour afficher la page d'accueil.
     * Inclut directement la vue associée.
     */
    public static function home()
    {
        include_once '../views/home/home.php';
    }

    /**
     * Méthode pour afficher la page d'inscription.
     * Inclut la vue d'inscription correspondante.
     */
    public static function register()
    {
        include_once '../views/auth/register/register.php';
    }

    /**
     * Méthode pour gérer les services liés à l'inscription.
     * Inclut le fichier de service d'inscription.
     */
    public static function registerService()
    {
        include_once '../views/auth/register/services/registerService.php';
    }

    /**
     * Méthode pour afficher la page de connexion.
     * Inclut directement la vue de connexion.
     */
    public static function login()
    {
        include_once '../views/auth/login/login.php';
    }

    /**
     * Méthode pour gérer les services liés à la connexion.
     * Inclut le fichier de service de connexion.
     */
    public static function loginService()
    {
        include_once '../views/auth/login/services/loginService.php';
    }

    /**
     * Méthode pour afficher le profil de l'utilisateur connecté.
     * Inclut la vue de profil.
     */
    public static function profile()
    {
        include_once '../views/profile/profile.php';
    }

    /**
     * Méthode pour afficher la page d'édition du profil.
     * Inclut le fichier de service associé aux modifications du profil.
     */
    public static function editProfile()
    {
        include_once '../views/profile/services/profileService.php';
    }

    /**
     * Méthode pour créer un nouveau post.
     * Inclut le fichier de service associé à la création de post.
     */
    public static function createPost()
    {
        include_once '../views/profile/services/postService.php';
    }

    /**
     * Méthode pour modifier un post existant.
     * Récupère le post via son ID et inclut la vue d'édition du post.
     *
     * @param int $id ID du post à modifier.
     */
    public static function editPost(int $id)
    {
        $postController = new PostController(); // Nouveau contrôleur pour gérer les posts
        $post = $postController->getPostById($id); // Récupérer le post depuis la base de données

        // Journaliser les données récupérées du post pour débogage
        error_log('data : ' . print_r($post, true));

        // Vérifier si le post existe
        if (!$post) {
            // Arrêter l'exécution si le post n'existe pas
            die("Le post avec l'ID $id n'existe pas.");
        }

        // Passe les données à la vue pour l'édition du post
        include_once '../views/profile/editPost/editPost.php';
    }

    /**
     * Méthode pour gérer les services liés à la modification d’un post.
     * Inclut le fichier de service correspondant.
     */
    public static function editPostService()
    {
        include_once '../views/profile/editPost/services/editPostService.php';
    }

    /**
     * Méthode pour supprimer un post existant.
     * Vérifie l'existence du post et inclut le fichier de service de suppression.
     *
     * @param int $id ID du post à supprimer.
     */
    public static function deletePost(int $id)
    {
        $postController = new PostController(); // Nouveau contrôleur pour gérer les posts
        $post = $postController->getPostById($id); // Récupère le post depuis la base de données

        // Vérifier si le post existe
        if (!$post) {
            // Arrêter l'exécution si le post n'existe pas
            die("Le post avec l'ID $id n'existe pas.");
        }

        // Inclure le fichier de service où la suppression sera exécutée
        include_once '../views/profile/services/postDeleteService.php';
    }

    public static function dashboardSkills(){
        include_once '../views/admin/skills/skills.php';
    }

    public static function skillsService(){
        include_once '../views/admin/services/skillsService.php';
    }
    public static function skillsDeleteService($id){

        $skillID = $id;

        include_once '../views/admin/services/skillsDeleteService.php';
    }
}