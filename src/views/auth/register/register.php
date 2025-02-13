<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

// Récupérer les erreurs éventuelles et les supprimer pour ne pas les afficher après rechargement
$errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : [];
$success = isset($_SESSION["success"]) ? $_SESSION["success"] : null;
unset($_SESSION["errors"]);
unset($_SESSION["success"]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscription</title>
    <style>
        <?php include "register.scss" ?>
    </style>
</head>
<body>
<main>
    <div class="container">
        <div class="title">
            <h1>Inscription</h1>

            <!-- Afficher les erreurs générales -->
            <?php if (!empty($errors)): ?>
                <ul class="error">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <form action="/register-controller" method="POST" class="register-form">
            <div class="input-field">
                <input type="text" name="firstname" id="firstname" placeholder="Prénom"
                       value="<?= isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '' ?>">
                <label for="firstname">
                    Prénom
                </label>
            </div>
            <div class="input-field">
                <input type="text" name="lastname" id="lastname" placeholder="Nom"
                       value="<?= isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '' ?>">
                <label for="lastname">
                    Nom
                </label>
            </div>
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
            <a href="/login" class="link">
                Déjà un compte ? Connectez-vous
            </a>
            <input type="submit" value="S'inscrire" class="btn-submit">
        </form>
    </div>
</main>
</body>
</html>