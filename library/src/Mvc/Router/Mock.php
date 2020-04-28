<?php

namespace Library\Mvc\Router;

use Phalcon\Mvc\Router\Route;

class Mock extends Route
{
    /** @noinspection MagicMethodsValidityInspection */
    public function __construct() {}

    public function __call($name, $arguments)
    {
        return $this;
    }
}
