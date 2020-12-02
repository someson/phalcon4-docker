<?php

namespace Library\Cli\Listeners;

use Phalcon\Events\Event;
use Library\Cli\Application as Console;
use Library\Cli\Handler;

class TaskListener
{
    protected $_cliHandler;

    public function __construct()
    {
        $this->_cliHandler = new Handler();
    }

    public function getHandler(): Handler
    {
        return $this->_cliHandler;
    }

    /**
     * Let boot the handler with entered console params:
     * -r = recording into DB
     * -s = run only one allowed instance
     * -d = debug
     *
     * @param Event $event
     * @param Console $console
     * @return bool
     */
    public function boot(Event $event, Console $console): bool
    {
        $handler = $this->getHandler();
        $args = $console->getArguments();
        $handler->setTask($args['task']);
        $handler->setAction($args['action']);

        $options = $console->getOptions();
        $handler->setRecording($options['r'] ?? $handler->isRecording());
        $handler->setSingleInstance($options['s'] ?? $handler->isSingleInstance());
        $handler->setDebug($options['d'] ?? $handler->isDebug());

        return ! $event->isStopped();
    }

    /**
     * @param Event $event
     * @return bool
     * @throws \RuntimeException
     */
    public function beforeHandleTask(Event $event): bool
    {
        $this->getHandler()->startTask();
        return ! $event->isStopped();
    }

    public function afterHandleTask(): void
    {
        $this->getHandler()->finishTask();
    }
}
