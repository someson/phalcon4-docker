<?php

namespace Library\Cli\Models;

use Phalcon\Db\RawValue;

class TaskRuntime extends Base\TaskRuntime
{
    public function beforeValidationOnCreate()
    {
        $this->create_time = new RawValue('NOW()');
    }

    public function initialize()
    {
        parent::initialize();
        $this->setConnectionService('dbCli');
    }
}
