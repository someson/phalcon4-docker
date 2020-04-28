<?php

namespace App\Modules\Frontend\Controllers;

use App\Version;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');

        $css->addCss('/assets/libs/bootstrap/css/bootstrap.min.css');
        $this->view->setVar('version', Version::releaseNice());
    }
}
