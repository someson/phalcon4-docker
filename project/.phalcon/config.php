<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: dirname(__DIR__));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('PUBLIC_PATH') || define('PUBLIC_PATH', BASE_PATH . '/public');

require_once BASE_PATH . '/library/src/Env.php';
require_once BASE_PATH . '/app/Version.php';

(new \Library\Env(BASE_PATH))->load();
function localEnv($name, $default = null) {
    return \Library\Env::get($name, $default);
}

return new \Phalcon\Config([
    'version' => \App\Version::get(),
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => localEnv('MYSQL_HOST'),
        'port'     => localEnv('MYSQL_PORT', 3306),
        'username' => localEnv('MYSQL_USERNAME'),
        'password' => localEnv('MYSQL_PASSWORD'),
        'dbname'   => localEnv('MYSQL_DATABASE'),
        'charset'  => localEnv('MYSQL_CHARSET'),
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => APP_PATH . '/Shared/Models/Base/',
        'migrationsDir'  => BASE_PATH . '/storage/migrations/',
        'cacheDir'       => BASE_PATH . '/storage/cache/',
        'baseUri'        => '/',
        'resourcesDir'   => PUBLIC_PATH . '/assets/', // not yet existend feature
    ],
    'printNewLine' => true
]);
