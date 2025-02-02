<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Services\JwtService;

// Récupération du jeton JWT
$jwt = $_COOKIE['access_token'] ?? null;

// Vérification du jeton JWT et récupération des données utilisateur
$userData = null;
if ($jwt) {
    $userData = JwtService::verifyToken($jwt);
}

// Validation de la connexion : un utilisateur est considéré comme connecté si le jeton est valide
$isConnected = $userData !== null;

// Gestion du chemin de l'avatar ou d'une image par défaut
$defaultAvatarPath = BASE_PATH . 'src/public/img/avatars/default.png';
$avatarPath = isset($userData['avatar'])
    ? BASE_PATH . 'src/public/img/' . $userData['avatar']
    : $defaultAvatarPath;

// Vérifier si le fichier d'avatar existe sur le serveur
if (!file_exists($avatarPath)) {
    $avatarPath = $defaultAvatarPath;
}

// Encodage de l'image pour afficher via base64
$imageData = base64_encode(file_get_contents($avatarPath));
$mimeType = mime_content_type($avatarPath);

?>
<div class="user-card">
    <?php if ($isConnected): ?>
        <div class="user-info">
            <div class="avatar-container">
                <img src="data:<?= htmlspecialchars($mimeType) ?>;base64,<?= htmlspecialchars($imageData) ?>"
                     alt="Avatar de l'utilisateur"
                     class="avatar" />
            </div>
            <div class="user-data">
                <p><?= htmlspecialchars($userData['firstname'] ?? 'Unknown') ?> <?=htmlspecialchars(strtoupper($userData['lastname']) ?? 'Unknown') ?></p>
                <p><?= htmlspecialchars($userData['email'] ?? 'Unknown') ?></p>
                <p>Role: <?= htmlspecialchars($userData['bio'] ?? 'Unknown') ?></p>
                <a href="/profile">Accéder à votre profil</a>
            </div>
        </div>
    <?php else: ?>
        <h1>Vous n'êtes pas connecté.</h1>
        <a href="/login">Se connecter</a>
    <?php endif; ?>
</div>