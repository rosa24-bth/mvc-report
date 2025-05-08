<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');

// Initiate db with migrations.
passthru('php bin/console doctrine:database:create --env=test --no-interaction');
passthru('php bin/console doctrine:migrations:migrate --env=test --no-interaction');

/*
if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
*/
