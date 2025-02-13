<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        <?= file_get_contents(BASE_PATH . 'src/views/profile/editPost/editPost.scss') ?>
    </style>
</head>
<body>
<section class="formEditPost" id="formEditPost">
    <div class="formEditPost-container">
        <form action="/profile/edit-post-service" method="POST"
              enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES) ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id'], ENT_QUOTES) ?>">

            <div>
                <h3>
                    Modification du post
                </h3>
            </div>

            <div>
                <div class="formEditPost-container-input">
                    <label for="title">Titre *</label>
                    <input
                            type="text"
                            name="title"
                            id="title"
                            value="<?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES) ?>"
                            required>
                </div>
                <div class="formEditPost-container-input">
                    <label for="description">Description *</label>
                    <input
                            type="text"
                            name="description"
                            id="description"
                            value="<?= htmlspecialchars($post['description'] ?? '', ENT_QUOTES) ?>"
                            required>
                </div>
                <div class="formEditPost-container-input">
                    <label for="external_link">Lien externe</label>
                    <input
                            type="text"
                            name="external_link"
                            id="external_link"
                            value="<?= htmlspecialchars($post['external_link'] ?? '', ENT_QUOTES) ?>">
                </div>
                <div class="formEditPost-container-input">
                    <label for="image_path">Image</label>
                    <input
                            type="file"
                            name="image_path"
                            id="image_path"
                            accept="image/*">

                    <div class="formEditPost-container-input-img">
                        <img
                                id="imagePreview"
                                src="<?= $_ENV['BASE_URL'] . '/img/posts/' ?><?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                    </div>
                </div>
            </div>
            <input type="submit" class="input-btn-maj" value="Mettre à jour le post">
        </form>
    </div>
</section>
<script>
    <?= file_get_contents(BASE_PATH . 'src/views/profile/profile.js') ?>
</script>
</body>
</html>