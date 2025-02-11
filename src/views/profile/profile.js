// Ouvre ou ferme le formulaire d'édition
function toggleForm() {
    // Récupère l'élément du formulaire avec l'ID 'formEdit'
    const formEdit = document.getElementById("formEdit");

    // Change la classe 'open' : l'ajoute si elle est absente, la retire si elle est présente
    formEdit.classList.toggle("open");
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
