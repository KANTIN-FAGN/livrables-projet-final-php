<?php

include_once BASE_PATH . 'src/includes/bootstrap.php';

// Chemin URL pour afficher l'image (public)
$defaultAvatarPathPublic = $_ENV['BASE_URL'] . '/img/avatars/default.png'; // Avatar par défaut
$avatarPathPublic = isset($userData['avatar']) && !empty($userData['avatar'])
    ? $_ENV['BASE_URL'] . '/img/avatars/' . $userData['avatar']
    : $defaultAvatarPathPublic;

// Chemin système pour vérifier l'existence du fichier (serveur)
$defaultAvatarPathFile = $_ENV['BASE_URL'] . '/img/avatars/default.png';; // Fichier par défaut
$avatarPathFile = isset($userData['avatar']) && !empty($userData['avatar'])
    ? $_ENV['BASE_URL'] . '/img/avatars/' . $userData['avatar']
    : $defaultAvatarPathFile;

// Validation : si le fichier n'existe pas, afficher le chemin de l'image par défaut
if (!file_exists($avatarPathFile)) {
    $avatarPathPublic = $defaultAvatarPathPublic; // Utiliser le chemin public par défaut
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <style>
        <?= file_get_contents(BASE_PATH . 'src/views/components/header/header.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/public/style.css') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/profile.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/components/postsCard/postsCard.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/components/formEdit/formEdit.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/components/formCreatePost/formCreatePost.scss') ?>
    </style>
    <script>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/profile.js') ?>
    </script>
</head>
<body>
<main>
    <?php include BASE_PATH . 'src/views/components/header/header.php'; ?>
    <section class="profile-container">
        <div class="profile-global">
            <div class="profile-edit-container">
                <button class="profile-edit-button" onclick="toggleForm()">
                    Modifier mon profil
                </button>
            </div>
            <div class="profile-avatar">
                <img src="<?= htmlspecialchars($avatarPathFile) ?>" alt="Photo de profil de <?= htmlspecialchars($userData['firstname'] . ' ' . $userData['lastname']) ?>">
            </div>
            <div class="profile-content">
                <div class="profile-content-global">
                    <h3 class="profile-name">
                        <?= $userData['firstname'] ?>
                        <?= strtoupper($userData['lastname'] ?? 'Unknown') ?>
                    </h3>
                    <p class="profile-bio">
                        <?= $userData['bio'] ?>
                    </p>
                    <?php if (!empty($userData['website_link'])): ?>
                        <div class="profile-website">
                            <a href="https://<?= htmlspecialchars($userData['website_link'], ENT_QUOTES, 'UTF-8') ?>"
                               target="_blank" rel="noopener noreferrer">
                                <?= htmlspecialchars($userData['website_link'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (!empty($userData['skills'])): ?>
            <div class="profile-skills">
                <h2>Compétences</h2>
                <div class="profile-skills-content">
                    <ul>
                        <?php foreach ($userData['skills'] as $skill): ?>
                            <li>
                                <span>
                                    <?= htmlspecialchars(is_object($skill) ? $skill->name : $skill['name'], ENT_QUOTES, 'UTF-8') ?>
                                    <strong><?= htmlspecialchars(is_object($skill) ? $skill->level : $skill['level'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="profile-skills">
                <h2>Compétences</h2>
                <div class="profile-skills-content">
                    <p>Aucune compétence renseignée.</p>
                </div>
            </div>
        <?php endif; ?>
        <div class="profile-posts">
            <div class="profile-posts-header">
                <h2>Posts</h2>
                <div >
                    <button class="profile-posts-button" onclick="toggleFormPost()">
                        Ajouter un post
                    </button>
                </div>
            </div>
            <div class="profile-posts-content">
                <?php include BASE_PATH . 'src/views/profile/components/postsCard/postsCard.php'; ?>
            </div>
        </div>
    </section>
    <?php include BASE_PATH . 'src/views/profile/components/formEdit/formEdit.php'; ?>
    <?php include BASE_PATH . 'src/views/profile/components/formCreatePost/formCreatePost.php'; ?>
</main>
</body>
</html>