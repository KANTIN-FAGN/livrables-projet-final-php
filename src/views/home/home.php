<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>accueil</title>
    <style>
        <?= file_get_contents(BASE_PATH . 'src/views/home/home.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/public/style.css') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/components/header/header.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/home/components/user-card/userCard.scss') ?>
    </style>

</head>
<body>
<main>
    <?php include BASE_PATH . 'src/views/components/header/header.php'; ?>
    <section class="container">
        <?php include BASE_PATH . 'src/views/home/components/user-card/userCard.php' ?>
        <div class="posts-container">
            <div class="create-post-container">

            </div>
        </div>
    </section>
</main>
</body>
</html>