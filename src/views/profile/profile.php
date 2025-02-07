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

$userWebsite = $userData['website'] ?? null;
$displayWebsite = $userWebsite ? htmlspecialchars($userWebsite, ENT_QUOTES, 'UTF-8') : 'Website';
$hrefWebsite = $userWebsite ? htmlspecialchars($userWebsite, ENT_QUOTES, 'UTF-8') : '#';

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
    </style>
</head>
<body>
<main>
    <?php include BASE_PATH . 'src/views/components/header/header.php'; ?>
    <section class="profile-container">
        <div class="profile-global">
            <div class="profile-edit-container">
                <a href="/profile/edit-profile/<?= $userData['id'] ?>">
                    <button class="profile-edit-button">
                        Modifier mon profil
                    </button>
                </a>
            </div>
            <div class="profile-avatar">
                <img src="<?= htmlspecialchars($avatarPathPublic) ?>" alt="">
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
            <h2>Posts</h2>
            <div class="profile-posts-content">
                <?php include BASE_PATH . 'src/views/profile/components/postsCard/postsCard.php'; ?>
            </div>
        </div>
    </section>
</main>
</body>
</html>