<?php

namespace App\Shared\Listeners;

use Library\Mvc\Dispatcher;
use Library\Http\Response\StatusCode as Status;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;

class HttpMethodListener extends Injectable
{
    public function beforeDispatch(Event $event, Dispatcher $dispatcher): bool
    {
        $allowedMethods = ['HEAD','GET','POST'];
        if (! \in_array($this->request->getMethod(), $allowedMethods, true)) {
            $this->response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
            $this->response->setHeader('Access-Control-Allow-Methods', 'HEAD,GET,POST');
            $this->response->resetHeaders()->setStatusCode(
                Status::METHOD_NOT_ALLOWED, Status::message(Status::METHOD_NOT_ALLOWED)
            );
            $event->stop();
        }

        return ! $event->isStopped();
    }
}
