function toggleForm() {
    // Récupère l'élément du formulaire avec l'ID 'formEdit'
    const formEdit = document.getElementById("formAddSkills");

    // Change la classe 'open' : l'ajoute si elle est absente, la retire si elle est présente
    formEdit.classList.toggle("open");
}