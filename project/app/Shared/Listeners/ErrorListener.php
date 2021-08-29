<?php

namespace App\Shared\Listeners;

use Library\Env;
use Library\Mvc\Dispatcher;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;

class ErrorListener
{
    private static int $_counter = 0;

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param \Exception $exception
     * @return bool|\Exception
     * @throws \Exception
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        if (++self::$_counter > 1) throw $exception;

        $dispatcher->addOptions([
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
        ]);

        if ($exception instanceof DispatchException) {
            switch ($exception->getCode()) {
                case DispatchException::EXCEPTION_INVALID_HANDLER:
                case DispatchException::EXCEPTION_CYCLIC_ROUTING:
                    $action = 'internalServerError';
                    break;
                case DispatchException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatchException::EXCEPTION_ACTION_NOT_FOUND:
                    $action = 'notFound';
                    break;
                case DispatchException::EXCEPTION_INVALID_PARAMS:
                    $action = 'badRequest';
                    break;
                default:
                    $action = 'unknownError';
            }
            $dispatcher->forward(['controller' => 'error', 'action' => $action]);
            return false;
        }

        if ($exception instanceof \Exception) {

            // blank screen shown if the exception thrown from the outside of dispatch loop,
            // in event (initialize) or in plugin like any service provider. As workaround
            // for production is any exception always rethrown to exception handler outside,
            // see WebApplication::handleException. For exception info .env → APP_DEBUG must be on.
            // related issues:
            // - https://github.com/phalcon/cphalcon/issues/2851
            // - https://github.com/phalcon/cphalcon/issues/11819
            if (Env::isProduction()) return $exception;

            // It is only possible to use forwarding if the exception thrown from inside of actions,
            // from outside of actions blank screen shown. As a workaround for development environment
            // we are doing no forwarding, just always allow bubbling exceptions till they are catched
            // in WebApplication::handleException from Debugger.
            $event->stop();
        }

        return $event->isStopped();
    }
}
