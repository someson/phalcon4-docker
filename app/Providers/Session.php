<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Manager;
use Library\Session\Adapter\Mysql;

class Session implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'session';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {

            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.sid_length', 32);

            /** @var \Phalcon\Di $this */
            $adapter = new Mysql([
                'connection' => $this->getShared('db'),
                'logger' => $this->getShared('logger'),
                'lifetime' => 86400,
                'ignoring_delay' => 900,
            ]);
            $sessionManager = new Manager();
            $sessionManager->setAdapter($adapter);
            if ($sessionManager->status() === Manager::SESSION_NONE) {
                $sessionManager->setName('SID');
                $sessionManager->start();
            }
            return $sessionManager;
        });
    }
}
