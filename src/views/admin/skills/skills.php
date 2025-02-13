<?php
include_once BASE_PATH . 'src/includes/bootstrap.php';

use App\controllers\SkillController;

$skillController = new SkillController();
$skills = $skillController->getSkills();

// Trier les compétences par ordre alphabétique (par exemple, selon le nom de la compétence)
usort($skills, function ($a, $b) {
    return strcmp($a['name'], $b['name']);
});
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Skills</title>
    <style>
        <?= file_get_contents(BASE_PATH . 'src/public/style.css') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/admin/components/navbar/navbar.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/admin/skills/skills.scss') ?>
        <?= file_get_contents(BASE_PATH . 'src/views/admin/components/formCreateSkills/formCreateSkills.scss') ?>
    </style>
</head>
<body>
<main class="main">
    <?php include BASE_PATH . 'src/views/admin/components/navbar/navbar.php'; ?>
    <div class="container">
        <div class="page-header">
            <h1>Skills</h1>
        </div>
        <div class="skills">
            <table class="table">
                <thead>
                <tr class="table-header">
                    <th>Skill</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($skills as $skill): ?>
                    <tr>
                        <td><?= htmlspecialchars($skill['name']) ?></td>
                        <td class="actions">
                            <a href="admin/delete/skill/<?= $skill['id'] ?>" class="action delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-trash-2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                    <line x1="10" x2="10" y1="11" y2="17"/>
                                    <line x1="14" x2="14" y1="11" y2="17"/>
                                </svg>
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="add-skill">
            <button class="btn" onclick="toggleForm()">
                Ajouter un skill
            </button>
        </div>
    </div>
    <?= file_get_contents(BASE_PATH . 'src/views/admin/components/formCreateSkills/formCreateSkills.php') ?>
</main>
<script>
    <?= file_get_contents(BASE_PATH . 'src/views/admin/admin.js') ?>
</script>
</body>
</html>