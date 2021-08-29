<?php

use Phalcon\DevTools\Bootstrap;

include 'webtools.config.php';
if (APPLICATION_ENV === ENV_PRODUCTION) {
    header('Location: https://phalcon4.test');
    die();
}
include PTOOLSPATH . '/bootstrap/autoload.php';

$bootstrap = new Bootstrap([
    'ptools_path' => PTOOLSPATH,
    'ptools_ip'   => PTOOLS_IP,
    'base_path'   => BASE_PATH,
]);

if (APPLICATION_ENV === ENV_TESTING) {
    return $bootstrap->run();
}
echo $bootstrap->run();
