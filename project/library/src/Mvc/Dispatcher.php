<?php

namespace Library\Mvc;

use Phalcon\Mvc\Dispatcher as BaseDispatcher;
use Library\Traits\TraitConfigurable;

class Dispatcher extends BaseDispatcher
{
    use TraitConfigurable;
}
