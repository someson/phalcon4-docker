<?php

namespace Library\Http\Filter;

use Phalcon\Di\Injectable;

class Ajax extends Injectable
{
    public function check()
    {
        /** @var \Phalcon\Http\Request $request */
        $request = $this->getDI()->getShared('request');
        return $request->isAjax();
    }
}