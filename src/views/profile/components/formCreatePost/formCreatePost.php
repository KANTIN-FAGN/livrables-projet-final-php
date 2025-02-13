<?php
?>

<section class="formPost" id="formPost">
    <div class="formPost-container">
        <div class="formPost-btn">
            <button onclick="toggleFormPost()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        <form action="/create/posts" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES) ?>">

            <div>
                <h3>
                    Création d'un post
                </h3>
            </div>

            <div>
                <div class="formPost-container-input">
                    <label for="title">Titre *</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="formPost-container-input">
                    <label for="description">Description *</label>
                    <input type="text" name="description" id="description" required>
                </div>
                <div class="formPost-container-input">
                    <label for="external_link">Lien externe</label>
                    <input type="text" name="external_link" id="external_link">
                </div>
                <div class="formPost-container-input">
                    <label for="image_path">Image</label>
                    <input type="file" name="image_path" id="image_path" accept="image/*">

                    <div class="formPost-container-input-img">
                        <!-- L'image sera affichée ici après sélection -->
                        <img id="imagePreview" src="" alt="">
                    </div>
                </div>
            </div>
            <input type="submit" class="input-btn-maj" value="Créer le post">
        </form>
    </div>
</section>
