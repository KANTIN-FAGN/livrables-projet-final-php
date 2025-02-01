<?php
session_start();
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
</head>
<body>
<h1>Bienvenue, <?= htmlspecialchars($userData['email']); ?> !</h1>
</body>
</html>