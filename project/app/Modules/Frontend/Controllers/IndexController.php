<?php

namespace App\Modules\Frontend\Controllers;

use Phalcon\Db\Enum;
use Phalcon\Db\Result\Pdo;
use Phalcon\Version;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');

        /** @var  $db */
        $db = $this->getDI()->getShared('db');
        /** @var Pdo $query */
        $query = $db->query("SHOW VARIABLES LIKE '%version%'");
        $mysqlVersion = '?';
        foreach ($result = $query->fetchAll(Enum::FETCH_ASSOC) as $item) {
            if ($item['Variable_name'] === 'version') {
                $mysqlVersion = $item['Value'];
                break;
            }
        }
        $css->addCss('/assets/libs/bootstrap/css/bootstrap.min.css');
        $this->view->setVars([
            'frameworkVersion' => Version::get(),
            'mysqlVersion' => $mysqlVersion,
            'webServerVersion' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] . ' + ssl + http2' : '?',
            'phpVersion' => $_SERVER['PHP_VERSION'] ?? '?',
        ]);
    }
}
