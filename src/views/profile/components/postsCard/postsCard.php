<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

?>

<section class="posts-card">
    <?php if (!empty($userData['posts'])): ?>
        <?php foreach ($userData['posts'] as $post): ?>
            <div class="post">
                <?php
                $title = is_object($post) ? $post->title : $post['title'];
                $description = is_object($post) ? $post->description : $post['description'];
                $image_path = is_object($post) ? $post->image_path : $post['image_path'];
                $created_at = is_object($post) ? $post->created_at : $post['created_at'];
                $id = is_object($post) ? $post->id : $post['id'];
                $external_link = is_object($post) ? $post->external_link : $post['external_link'];
                ?>
                <div class="btn-edit">
                    <a href="/profile/delete-post/<?= urlencode($id) ?>">
                        <button class="profile-delete-button">
                            Supprimer le post
                        </button>
                    </a>
                    <a href="/profile/edit-post/<?= urlencode($id) ?>">
                        <button class="profile-edit-button">
                            Modifier le post
                        </button>
                    </a>
                </div>
                <div class="post-date">
                    <span><?= htmlspecialchars($created_at, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="post-title">
                    <h3><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
                </div>
                <div class="post-description">
                    <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="post-link">
                    <a href="<?= htmlspecialchars($external_link ?? '#', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($external_link ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
                <div class="post-image">
                    <img src="<?= $_ENV['BASE_URL']. '/img/posts/' ?><?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun post disponible.</p>
    <?php endif; ?>
</section>