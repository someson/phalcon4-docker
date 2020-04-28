<?php

if (! function_exists('env')) {
    function env($name, $default = null) {
        return \Library\Env::get($name, $default);
    }
}

if (! function_exists('__')) {
    function __($key = null, array $params = []) {
        return $key ? \App\Translator::instance()->_($key, $params) : '[TRANSLATION FAILED]';
    }
    function __g($groupName, $key, array $params = []) {
        return \App\Translator::instance()->g($groupName, $key, $params);
    }
}
