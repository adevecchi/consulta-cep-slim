<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(['settings' => require __DIR__ . '/../config/settings.php']);

require  __DIR__ . '/container.php';

require __DIR__ . '/middleware.php';

require __DIR__ . '/routes.php';

return $app;