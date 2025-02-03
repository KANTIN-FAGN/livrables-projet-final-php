<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Services\JwtService;

// Définir BASE_PATH si elle n'existe pas
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 4) . '/'); // On remonte jusqu'à la racine du projet
}

// Charger les variables d'environnement si elles ne sont pas déjà définies
if (!isset($_ENV['BASE_URL'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

// Récupération du jeton JWT
$jwt = $_COOKIE['access_token'] ?? null;

// Vérification du jeton JWT et récupération des données utilisateur
$userData = null;
if ($jwt) {
    $userData = JwtService::verifyToken($jwt);
}

// Validation de la connexion : un utilisateur est considéré connecté si le jeton est valide
$isConnected = $userData !== null;

// Gestion du chemin de l'avatar
$defaultAvatarPathPublic = $_ENV['BASE_URL'] . '/img/avatars/default.png';
$defaultAvatarPathFile = BASE_PATH . 'public/img/avatars/default.png'; // Chemin système

$avatarPathPublic = isset($userData['avatar'])
    ? $_ENV['BASE_URL'] . '/img/avatars/' . $userData['avatar']
    : $defaultAvatarPathPublic;

$avatarPathFile = isset($userData['avatar'])
    ? BASE_PATH . 'public/img/avatars/' . $userData['avatar']
    : $defaultAvatarPathFile;

// Vérification de l'existence du fichier sur le serveur
if (!file_exists($avatarPathFile)) {
    $avatarPathPublic = $defaultAvatarPathPublic;
}
?>

<div class="user-card">
    <?php if ($isConnected): ?>
        <div class="user-info">
            <div class="avatar-container">
                <img src="<?= htmlspecialchars($avatarPathPublic) ?>" alt="Avatar utilisateur" class="avatar"/>
            </div>
            <div class="user-data">
                <p><?= htmlspecialchars($userData['firstname'] ?? 'Unknown') ?> <?= htmlspecialchars(strtoupper($userData['lastname'] ?? 'Unknown')) ?></p>
                <p><?= htmlspecialchars($userData['email'] ?? 'Unknown') ?></p>
                <p>Rôle : <?= htmlspecialchars($userData['bio'] ?? 'Unknown') ?></p>
                <a href="/profile">Accéder à votre profil</a>
            </div>
        </div>
    <?php else: ?>
        <h1>Vous n'êtes pas connecté.</h1>
        <a href="/login">Se connecter</a>
    <?php endif; ?>
</div>