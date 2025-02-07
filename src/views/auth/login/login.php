<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

// Récupérer les erreurs éventuelles et les supprimer pour ne pas les afficher après rechargement
$errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : [];
unset($_SESSION["errors"]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion</title>
    <style>
        <?php include "login.scss" ?>
    </style>
</head>
<body>
<main>
    <div class="container">
        <div class="title">
            <h1>Connexion</h1>

            <!-- Afficher les erreurs générales -->
            <?php if (!empty($errors)): ?>
                <ul class="error">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <form action="/login-controller" method="POST" class="login-form">
            <div class="input-field">
                <input type="email" name="email" id="email" placeholder="Email"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                <label for="email">
                    Email
                </label>
            </div>
            <div class="input-field">
                <input type="password" name="password" id="password" placeholder="Mot de passe">
                <label for="password">
                    Mot de passe
                </label>
            </div>
            <input type="submit" value="Connexion" class="btn-submit">
        </form>
    </div>
</main>
</body>
</html>