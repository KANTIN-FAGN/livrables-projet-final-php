<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

use App\controllers\SkillController;

$skillController = new SkillController();
$skills = $skillController->getSkills();

?>

<section class="formEdit" id="formEdit">
    <div class="formEdit-container">
        <div class="formEdit-btn">
            <button onclick="toggleForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        <form action="/profile/edit" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES) ?>">

            <div>
                <h3>
                    Informations personnelles
                </h3>
                <div class="formEdit-container-input">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname"
                           value="<?= htmlspecialchars($userData['firstname'], ENT_QUOTES) ?>" required>
                </div>
                <div class="formEdit-container-input">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" id="lastname"
                           value="<?= htmlspecialchars($userData['lastname'], ENT_QUOTES) ?>" required>
                </div>
                <div class="formEdit-container-input">
                    <label for="bio">Biographie</label>
                    <textarea name="bio" id="bio"
                              required><?= htmlspecialchars($userData['bio'], ENT_QUOTES) ?></textarea>
                </div>
                <div class="formEdit-container-input">
                    <label for="website">Site web</label>
                    <input type="text" name="website" id="website"
                           value="<?= htmlspecialchars($userData['website_link'], ENT_QUOTES) ?>">
                </div>
            </div>
            <div class="formEdit-hr">
                <hr>
            </div>
            <div id="skills-wrapper">
                <h3>
                    Compétences
                </h3>
                <?php if (empty($userData['skills'])): ?>
                    <div class="skill-entry">
                        <label>Compétence :</label>
                        <select name="skills[]" class="skills">
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?= htmlspecialchars($skill['id'], ENT_QUOTES) ?>"
                                    <?= isset($userSkill->name) && $skill['name'] == $userSkill->name ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($skill['name'], ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>Niveau :</label>
                        <select name="levels[]" class="levels">
                            <option value="Débutant">
                                Débutant
                            </option>
                            <option value="Intermédiaire">
                                Intermédiaire
                            </option>
                            <option value="Avancé">
                                Avancé
                            </option>
                            <option value="Expert">
                                Expert
                            </option>
                        </select>

                        <button class="delete-btn" type="button" onclick="removeSkill(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-trash">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>
                <?php foreach ($userData['skills'] as $userSkill): ?>
                    <div class="skill-entry">
                        <label>Compétence :</label>
                        <select name="skills[]" class="skills">
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?= htmlspecialchars($skill['id'], ENT_QUOTES) ?>"
                                    <?= isset($userSkill->name) && $skill['name'] == $userSkill->name ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($skill['name'], ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>Niveau :</label>
                        <select name="levels[]" class="levels">
                            <option value="Débutant" <?= isset($userSkill->level) && $userSkill->level === 'Débutant' ? 'selected' : '' ?>>
                                Débutant
                            </option>
                            <option value="Intermédiaire" <?= isset($userSkill->level) && $userSkill->level === 'intermédiaire' ? 'selected' : '' ?>>
                                Intermédiaire
                            </option>
                            <option value="Avancé" <?= isset($userSkill->level) && $userSkill->level === 'avancé' ? 'selected' : '' ?>>
                                Avancé
                            </option>
                            <option value="Expert" <?= isset($userSkill->level) && $userSkill->level === 'Expert' ? 'selected' : '' ?>>
                                Expert
                            </option>
                        </select>

                        <button class="delete-btn" type="button" onclick="removeSkill(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-trash">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-skill-btn" onclick="addSkill()">
                Ajouter une compétence
            </button>

            <input type="submit" class="input-btn-maj" value="Mettre à jour">
        </form>
    </div>
</section>