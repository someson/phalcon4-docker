<?php
/** @var \Codeception\Module\Phalcon4 $this */

$root = dirname(__DIR__, 2);

require_once $root . '/app/Constants.php';
require_once $root . '/app/Functions.php';
require_once $root . '/vendor/autoload.php';

defined('TEST_FIXTURES') || define('TEST_FIXTURES', BASE_DIR . DS . 'tests' . DS . '_fixtures');

(new \Library\Env(dirname(__DIR__)))->load();

$moduleConfig = $this->_getConfig();
$domain = $moduleConfig['site'] ?? env('DEFAULT_DOMAIN');

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] ?? BASE_DIR;
$_SERVER['SERVER_NAME']   = $_SERVER['SERVER_NAME'] ?? $domain ?? env('DEFAULT_DOMAIN');

//$config = \Codeception\Configuration::config();
//$settings = \Codeception\Configuration::suiteSettings('unit', $config);
//$suiteSettings = \Codeception\Configuration::suiteEnvironments('unit');

return (new \App\Bootstrap($domain, false))->getApplication();
