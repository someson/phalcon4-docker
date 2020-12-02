<?php

namespace Library\Cli;

use Phalcon\Cli\Dispatcher as BaseDispatcher;
use Library\Traits\TraitConfigurable;

class Dispatcher extends BaseDispatcher
{
    use TraitConfigurable;
}
