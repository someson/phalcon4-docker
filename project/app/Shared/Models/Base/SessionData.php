<?php

namespace App\Shared\Models\Base;

class SessionData extends \Library\Models\ModelBase
{
    public string $id;
    public string $data;
    public string $created_on;
    public string $modified_on;

    public function initialize()
    {
        $this->setSchema(env('MYSQL_DATABASE'));
        $this->setSource('session_data');
    }
}
