<?php

namespace App\Modules\Frontend;

use App\Shared\Listeners\{ ErrorListener, HttpMethodListener };
use Library\Mvc\Dispatcher;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public const SESSION_PREFIX = 'F_';

    public function registerAutoloaders(DiInterface $container = null)
    {
    }

    /**
     * @param DiInterface|null $container
     * @throws \UnexpectedValueException
     */
    public function registerServices(DiInterface $container)
    {
        /** @var \Phalcon\Events\Manager $eventsManager */
        $eventsManager = $container->getShared('eventsManager');
        $eventsManager->attach('view', new Listeners\ViewListener());

        $container->setShared('dispatcher', function() use ($eventsManager) {
            /** @var \Phalcon\Di $this */
            $eventsManager->attach('dispatch', new ErrorListener());
            $eventsManager->attach('dispatch', new HttpMethodListener());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\\Controllers');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        $container['session']->setOptions(['uniqueId' => self::SESSION_PREFIX]);
        $container->getService('session')->resolve();

        $container['view']->addViewsDir([__DIR__.'/Views/', SHARED_DIR.'/Views/']);
        $container['view']->setEventsManager($eventsManager);
        $container['view']->setVar('site', $container['config']->get('app', []));

        $container['router']->notFound(['controller' => 'error', 'action' => 'notFound']);
    }
}
