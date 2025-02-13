<?php

namespace App\Controllers;

use App\Models\SkillModel;

class SkillController
{
    private $skillModel; // Instance du modèle SkillModel

    /**
     * Constructeur de la classe SkillController
     * Initialise une instance de SkillModel pour interagir avec les données des compétences
     */
    public function __construct() {
        // Création de l'instance du modèle pour récupérer les compétences depuis la base de données
        $this->skillModel = new SkillModel();
    }

    public function createSkill(string $name) {
        $skill = $this->skillModel->createSkill($name);
        return $skill;
    }

    /**
     * Récupère toutes les compétences disponibles via le modèle
     *
     * @return array|bool Tableau contenant les compétences (ou false en cas d'erreur)
     */
    public function getSkills()
    {
        // Appel de la méthode `getAllSkills` sur le modèle pour récupérer les données des compétences
        $skills = $this->skillModel->getAllSkills();

        // Retourne les compétences au format récupéré depuis le modèle
        return $skills;
    }

    /**
     * Supprime une compétence (skill) spécifique.
     *
     * Cette méthode appelle le modèle `skillModel` pour supprimer une compétence
     * identifiée par son ID dans la base de données.
     *
     * @param int $id Identifiant de la compétence à supprimer.
     *
     * @return bool Indique si la suppression a réussi (`true`) ou échoué (`false`).
     */
    public function deleteSkill(int $id): bool
    {
        // Appel du modèle pour exécuter la suppression de la compétence
        $result = $this->skillModel->deleteSkill($id);

        // Retourne le résultat de l'opération (succès ou échec)
        return $result;
    }
}