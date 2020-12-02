<?php

namespace Library\Cli;

use Phalcon\Di;
use Phalcon\Db\Profiler;
use Phalcon\Events\{ Event, Manager };

class Profile extends Manager
{
    public function __construct()
    {
        $di = Di::getDefault();

        $di->set('profiler', function() {
            return new Profiler();
        }, true);

        $this->attach('db', function(Event $event, $connection) {
            /** @var Profiler $profiler */
            /** @var $this Di */
            $profiler = $this->get('profiler');
            if ($event->getType() === 'beforeQuery') {
                /** @var \Phalcon\Db\AdapterInterface $connection */
                $statement = $connection->getSQLStatement();
                $profiler->startProfile($statement);
            }
            if ($event->getType() === 'afterQuery') {
                $profiler->stopProfile();
            }
        });
    }
}
