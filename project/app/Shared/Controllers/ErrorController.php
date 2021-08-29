<?php

namespace App\Shared\Controllers;

use Library\Http\Response\StatusCode as Status;
use Library\Mvc\Dispatcher;
use Phalcon\Config;
use Phalcon\Mvc\View;

/**
 * Class ErrorController
 * @package App\Shared\Controllers
 *
 * @property Dispatcher dispatcher
 * @property Config config
 */
class ErrorController extends ControllerBase
{
    public function initialize(): void
    {
        $this->view->disableLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setMainView('error');
    }

    protected function prepareErrorTemplate(int $statusCode, string $statusMessage)
    {
        $this->tag::setTitle($statusMessage);
        $this->response->resetHeaders()->setStatusCode($statusCode, $statusMessage);

        if ($exception = $this->dispatcher->getOption('exceptionData')) {
            $this->view->setVar('exceptionData', (object) $exception);
        }
        $this->view->setVars([
            'errCode' => $statusCode,
            'errMessage' => $exception->message ?? $statusMessage,
            'site' => $this->config->get('app'),
        ]);
    }

    public function badRequestAction(): void
    {
        $this->prepareErrorTemplate(Status::BAD_REQUEST, Status::message(Status::BAD_REQUEST));
    }

    public function notFoundAction(): void
    {
        $this->prepareErrorTemplate(Status::NOT_FOUND, Status::message(Status::NOT_FOUND));
    }

    public function internalServerErrorAction(): void
    {
        $this->prepareErrorTemplate(
            Status::INTERNAL_SERVER_ERROR, Status::message(Status::INTERNAL_SERVER_ERROR)
        );
    }

    public function unknownErrorAction(): void
    {
        $this->dispatcher->forward([
            'controller' => 'error',
            'action' => 'internalServerError',
        ]);
    }
}
