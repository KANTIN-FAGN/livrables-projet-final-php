<?php
session_start();

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
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<h1>Inscription</h1>

<!-- Affiche un message de succès -->
<?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<!-- Afficher les erreurs générales -->
<?php if (!empty($errors)): ?>
    <ul class="error">
        <?php foreach ($errors as $field => $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="services/registerService.php" method="POST">
    <input type="text" name="firstname" id="firstname" placeholder="Prénom"
           value="<?= isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '' ?>">
    <br>
    <input type="text" name="lastname" id="lastname" placeholder="Nom"
           value="<?= isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '' ?>">
    <br>
    <input type="email" name="email" id="email" placeholder="Email"
           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    <br>
    <input type="password" name="password" id="password" placeholder="Mot de passe">
    <br>
    <input type="submit" value="Envoyer">
</form>
</body>
</html>