// Ouvre ou ferme le formulaire d'édition
function toggleForm() {
    // Récupère l'élément du formulaire avec l'ID 'formEdit'
    const formEdit = document.getElementById("formEdit");

    // Change la classe 'open' : l'ajoute si elle est absente, la retire si elle est présente
    formEdit.classList.toggle("open");
}

// Ouvre ou ferme le formulaire de création de posts
function toggleFormPost() {
    // Récupère l'élément du formulaire avec l'ID 'formPost'
    const formPost = document.getElementById("formPost");

    // Change la classe 'open' : l'ajoute si elle est absente, la retire si elle est présente
    formPost.classList.toggle("open");
}

// Ouvre le formulaire de modification d'un post et pré-remplit les champs
function toggleFormEditPost(postData = null) {
    const formEditPost = document.getElementById("editPost");

    // Si des données sont fournies, pré-remplit le formulaire
    if (postData) {
        const titleInput = document.getElementById("title");
        const descriptionInput = document.getElementById("description");
        const externalLinkInput = document.getElementById("external_link");
        const imagePreview = document.getElementById("imagePreview"); // Aperçu de l'image

        // Mettre à jour les champs avec les valeurs récupérées
        titleInput.value = postData.title || ""; // Met à jour le champ 'Titre'
        descriptionInput.value = postData.description || ""; // Met à jour le champ 'Description'
        externalLinkInput.value = postData.external_link || ""; // Met à jour le champ 'Lien externe'

        // Mise à jour de l'aperçu de l'image si un chemin d'image est présent
        if (postData.image_path) {
            imagePreview.src = postData.image_path;
            imagePreview.style.display = "block"; // Affiche l'aperçu si une image existe
        } else {
            imagePreview.src = "";
            imagePreview.style.display = "none"; // Masque l'aperçu si aucune image n'existe
        }
        console.log(postData);
    } else {
        console.warn("Aucune donnée de post trouvée.");
    }

    // Affiche ou masque le formulaire
    formEditPost.classList.toggle("open");
}

// Ajoute un nouvel élément de compétence dans la section des compétences
function addSkill() {
    const container = document.getElementById('skills-wrapper');

    // Vérifie si l'élément parent existe
    if (!container) {
        console.error("Le conteneur des compétences (skills-wrapper) n'existe pas.");
        return;
    }

    // Clone un élément avec la classe '.skill-entry'
    const newSkill = document.querySelector('.skill-entry').cloneNode(true);

    // Réinitialise le champ de sélection des compétences dans le nouvel élément
    const skillSelect = newSkill.querySelector('.skills');
    if (skillSelect) skillSelect.value = ""; // Remet à zéro ou vide la sélection

    // Réinitialise le champ de sélection des niveaux
    const levelSelect = newSkill.querySelector('.levels');
    if (levelSelect) levelSelect.value = ""; // Idem ici, choix vide par défaut

    // Ajoute le nouvel élément au conteneur
    container.appendChild(newSkill);
}

// Met à jour les options de compétences pour éviter les valeurs en double
function updateSkillOptions() {
    // Récupère tous les champs de sélection de compétences
    const allSkills = document.querySelectorAll('.skills');

    // Récupère toutes les valeurs actuellement sélectionnées
    const selectedValues = Array.from(allSkills).map(select => select.value);

    // Parcourt chaque menu déroulant
    allSkills.forEach(select => {
        // Parcourt les options de chaque menu déroulant
        const options = select.querySelectorAll('option');
        options.forEach(option => {
            // Si une option est déjà choisie (par un autre champ) et qu'elle n'est pas dans le champ actuel
            if (selectedValues.includes(option.value) && option.value !== select.value) {
                option.style.display = 'none'; // Masque cette option
            } else {
                option.style.display = ''; // Remet l'option disponible
            }
        });
    });
}

// Écoute l'événement 'DOMContentLoaded' afin de s'assurer que le DOM est complètement chargé avant d'exécuter du JavaScript.
document.addEventListener('DOMContentLoaded', function () {
    // Récupère l'élément HTML pour l'input de type "file" (champ de téléchargement) par son ID
    const avatarInput = document.getElementById('avatar');

    // Récupère l'élément HTML pour l'image utilisée comme aperçu/avatar actuel
    const avatarPreview = document.getElementById('avatar-preview');

    // Vérifie si les deux éléments requis 'avatarInput' ou 'avatarPreview' existent.
    // Si l'un d'eux est manquant, affiche un message d'erreur dans la console et stoppe l'exécution du script.
    if (!avatarInput || !avatarPreview) {
        console.error("Les éléments nécessaires (avatar ou avatar-preview) n'existent pas.");
        return;
    }

    // Ajoute un gestionnaire d'événement 'change' sur l'input "file".
    // Cet événement est déclenché chaque fois qu'un utilisateur sélectionne un fichier depuis son ordinateur.
    avatarInput.addEventListener('change', function (event) {
        // Récupère le premier fichier sélectionné par l'utilisateur à partir de l'input "file".
        const file = event.target.files[0];

        if (file) { // Vérifie si un fichier a effectivement été sélectionné
            // Vérifie si le fichier sélectionné est bien un fichier image (type MIME comme image/png, image/jpeg, etc.)
            if (!file.type.match('image.*')) {
                alert("Veuillez sélectionner un fichier d'image valide."); // Alerte s'il ne s'agit pas d'une image
                return; // Interrompt le traitement
            }

            // Crée une instance de FileReader, un objet permettant de lire le contenu d'un fichier en JavaScript
            const reader = new FileReader();

            // Ajoute un gestionnaire d'événements 'onload'. Cet événement est déclenché lorsque FileReader termine de lire le fichier.
            reader.onload = function (e) {
                // Met à jour l'attribut 'src' de l'image d'aperçu avec le contenu lu (sous forme de DataURL).
                // Cela permet d'afficher le fichier immédiatement en tant qu'image.
                avatarPreview.src = e.target.result;
            };

            // Déclenche la lecture du fichier en tant que DataURL (représentation base64 du fichier)
            // Cette méthode lit le fichier et génère un lien directement utilisable dans une balise <img>.
            reader.readAsDataURL(file);
        } else {
            // Si aucun fichier n'est sélectionné, affiche un message d'avertissement dans la console
            console.warn("Aucun fichier sélectionné.");
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner les éléments nécessaires
    const inputFile = document.getElementById('image_path'); // L'élément input file
    const imagePreview = document.getElementById('imagePreview'); // L'élément <img> pour la prévisualisation

    // Vérification si les éléments existent pour éviter des erreurs
    if (!inputFile || !imagePreview) {
        console.error("Les éléments nécessaires (image_path ou imagePreview) sont introuvables.");
        return; // Arrête le script si les éléments ne sont pas trouvés
    }

    // Ajouter un gestionnaire d'événement 'change' à l'input
    inputFile.addEventListener('change', function (event) {
        const file = event.target.files[0]; // Récupère le fichier sélectionné

        if (file) {
            // Vérifie si le fichier est une image valide
            if (!file.type.startsWith('image/')) {
                alert("Veuillez sélectionner une image valide !"); // Message d'erreur si ce n'est pas une image
                imagePreview.src = ''; // Réinitialise la prévisualisation
                imagePreview.alt = ''; // Réinitialise l'attribut alt
                return;
            }

            const reader = new FileReader(); // Crée un nouvel objet FileReader

            // Une fois l'image chargée, met à jour l'attribut 'src' de l'élément <img>
            reader.onload = function (e) {
                imagePreview.src = e.target.result; // Affecte l'image chargée à l'élément <img>
                imagePreview.alt = file.name;
                imagePreview.style.width = '100%';
                imagePreview.style.height = '350px';
                imagePreview.style.objectFit = 'contain';
            };

            reader.readAsDataURL(file); // Lit l'image comme une URL de données
        } else {
            // Réinitialise la prévisualisation si aucun fichier n'est sélectionné
            imagePreview.src = '';
        }
    });
});

// Écouteur d'événement sur le conteneur des compétences pour détecter les changements
document.getElementById('skills-wrapper').addEventListener('change', updateSkillOptions);

// Supprime un champ de compétence (si au moins un reste)
function removeSkill(button) {
    const container = document.getElementById('skills-wrapper');

    // Vérifie que le conteneur contient plus d'un enfant
    if (container.children.length > 1) {
        button.parentElement.remove(); // Supprime l'élément parent (ligne de compétence)
    } else {
        alert("Il doit y avoir au moins une compétence."); // Bloque la suppression si c'est la dernière compétence
    }
}