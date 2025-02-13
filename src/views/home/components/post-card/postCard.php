<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

use App\controllers\PostController;
$postController = new PostController();

$posts = $postController->getPosts();
?>

<section class="posts-card">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-date">
                    <span><?= htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="post-title">
                    <h3><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                </div>
                <div class="post-description">
                    <p><?= htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="post-link">
                    <a href="<?= htmlspecialchars($post['external_link'] ?? '#', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($post['external_link'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
                <div class="post-image">
                    <img src="<?= $_ENV['BASE_URL'] . '/img/posts/' . htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                         alt="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun post disponible.</p>
    <?php endif; ?>
</section>