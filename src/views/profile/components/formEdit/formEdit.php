<?php

?>

<section class="formEdit" id="formEdit">
    <button onclick="toggleForm()">Fermer</button>
    <div class="formEdit-container">
        <form action="/profile/edit" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES) ?>"> <!-- Champ caché pour l'ID -->

            <div class="formEdit-container-input">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($userData['firstname'], ENT_QUOTES) ?>" required>
            </div>

            <div class="formEdit-container-input">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($userData['lastname'], ENT_QUOTES) ?>" required>
            </div>

            <div class="formEdit-container-input">
                <label for="bio">Biographie</label>
                <textarea name="bio" id="bio" required><?= htmlspecialchars($userData['bio'], ENT_QUOTES) ?></textarea>
            </div>

            <div class="formEdit-container-input">
                <label for="website">Site web</label>
                <input type="text" name="website" id="website" value="<?= htmlspecialchars($userData['website_link'], ENT_QUOTES) ?>">
            </div>

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
</section>
