<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

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
                <p><?= htmlspecialchars($userData['bio'] ?? 'Unknown') ?></p>
                <a href="/profile">Accéder à votre profil</a>
            </div>
        </div>
    <?php else: ?>
        <h1>Vous n'êtes pas connecté.</h1>
        <a href="/login">Se connecter</a>
    <?php endif; ?>
</div>