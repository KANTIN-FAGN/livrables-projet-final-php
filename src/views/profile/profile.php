<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Services\JwtService;

$jwt = $_COOKIE['access_token'] ?? null;

if (!$jwt || !JwtService::verifyToken($jwt)) {
    header('Location: /login');
    exit;
}

$userData = JwtService::verifyToken($jwt);
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
    </style>
</head>
<body>
<main>
    <?php include BASE_PATH . 'src/views/components/header/header.php'; ?>
</main>
</body>
</html>