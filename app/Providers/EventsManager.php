<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Events\Manager;

class EventsManager implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'eventsManager';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            $obj = new Manager();
            $obj->enablePriorities(true);
            return $obj;
        });
    }
}
