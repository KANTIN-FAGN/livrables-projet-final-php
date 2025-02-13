<?php

?>

<section class="fromCreateSkills" id="formAddSkills">
    <div class="fromCreateSkills-container">
        <div class="fromCreateSkills-btn">
            <button onclick="toggleForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        <form action="/admin/create/skills" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES) ?>">
            <div>
                <h3>
                    Creation de la compétence
                </h3>
                <div class="fromCreateSkills-container-input">
                    <label for="name">Nom de la compétence</label>
                    <input type="text" name="name" id="name" required>
                </div>
            </div>
            <input type="submit" class="input-btn-maj" value="Créer la compétence">
        </form>
    </div>
</section>