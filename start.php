<?php
require 'vendor/autoload.php';

$port = getenv('APP_PORT') ?: '8000';

echo PHP_EOL;
echo "--------------------------------------------" . PHP_EOL;
echo "  🚀 Serveur PHP démarré !" . PHP_EOL;
echo "  ➡️  Accédez à : http://localhost:$port" . PHP_EOL;
echo "--------------------------------------------" . PHP_EOL;
echo PHP_EOL;

passthru("php -S localhost:$port -t src/public");