<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: dirname(__DIR__, 2));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'version' => '1.0',
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'mariadb-service',
        'username' => 'phalcon4',
        'password' => 'secret',
        'dbname'   => 'phalcon4',
        'charset'  => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => APP_PATH . '/Shared/Models/Base/',
        'migrationsDir'  => BASE_PATH . '/storage/migrations/',
        'cacheDir'       => BASE_PATH . '/storage/cache/',
        'baseUri'        => '/',
    ],
    'printNewLine' => true
]);
