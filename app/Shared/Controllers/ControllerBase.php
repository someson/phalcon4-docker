<?php

namespace App\Shared\Controllers;

use Phalcon\Mvc\Controller;
use Library\Http\Response\StatusCode;
use Library\Traits\TraitTrackable;

/**
 * Class ControllerBase
 * @package App\Shared\Controllers
 */
abstract class ControllerBase extends Controller
{
    use TraitTrackable;

    public function initialize(): void
    {
        $config = $this->getDI()->getShared('config');
        /** @var \Phalcon\Config $exclude */
        $exclude = $config->path('notrack.exclude', []);
        $this->makeTrackable('notrack', $exclude ? $exclude->toArray() : []);
    }

    protected function getModulePrefix(): ?string
    {
        /** @var \Phalcon\Mvc\Router\GroupInterface $group */
        $group = $this->router->getMatchedRoute()->getGroup();
        return $group ? trim($group->getPrefix(), ' /') : null;
    }

    /**
     * @param mixed $to
     * @param bool $external
     * @param int $statusCode
     * @return bool
     */
    protected function redirect($to = null, bool $external = false, int $statusCode = StatusCode::FOUND): bool
    {
        $parts = [];
        if ($prefix = $this->getModulePrefix()) {
            $parts[0] = trim($prefix, '/ ');
        }
        $target = explode('/', trim($to, '/ '));
        if (isset($parts[0]) && $target[0] === $parts[0]) {
            array_shift($target);
        }
        $parts = array_merge($parts, $target);
        $result = \count($parts) ? implode('/', $parts) : null;

        $this->response->redirect($result, $external, $statusCode);
        return false;
    }

    public function nothingFound(): void
    {
        $statusMessage = StatusCode::message(StatusCode::NOT_FOUND);
        $this->tag::setTitle($statusMessage);
        $this->response->resetHeaders()->setStatusCode(StatusCode::NOT_FOUND, $statusMessage);
    }
}
