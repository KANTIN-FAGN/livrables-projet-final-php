<?php
// router.php

// Redirige les fichiers existants (CSS, JS, images, etc.)
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . $_SERVER['REQUEST_URI'])) {
    return false;
}

// Sinon, passe toutes les requêtes à index.php
require_once __DIR__ . '/index.php';