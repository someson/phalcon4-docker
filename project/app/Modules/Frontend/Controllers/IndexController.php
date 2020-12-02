<?php

namespace App\Modules\Frontend\Controllers;

use App\Version;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');

        /** @var  $db */
        $db = $this->getDI()->getShared('db');
        /** @var \Phalcon\Db\Result\Pdo $query */
        $query = $db->query("SHOW VARIABLES LIKE '%version%'");
        $mysqlVersion = '?';
        foreach ($result = $query->fetchAll(\Phalcon\Db\Enum::FETCH_ASSOC) as $item) {
            if ($item['Variable_name'] === 'version') {
                $mysqlVersion = $item['Value'];
                break;
            }
        }
        $css->addCss('/assets/libs/bootstrap/css/bootstrap.min.css');
        $this->view->setVars([
            'frameworkVersion' => \Phalcon\Version::get(),
            'mysqlVersion' => $mysqlVersion,
            'webServerVersion' => $_SERVER['SERVER_SOFTWARE'] ?? '?',
            'phpVersion' => $_SERVER['PHP_VERSION'] ?? '?',
        ]);
    }
}
