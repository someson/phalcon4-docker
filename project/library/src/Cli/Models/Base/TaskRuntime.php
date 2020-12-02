<?php

namespace Library\Cli\Models\Base;

class TaskRuntime extends \Library\Models\ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $file;

    /**
     *
     * @var integer
     */
    public $line;

    /**
     *
     * @var integer
     */
    public $error_type;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var string
     */
    public $server_name;

    /**
     *
     * @var string
     */
    public $execution_script;

    /**
     *
     * @var integer
     */
    public $pid;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("task_runtime");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'task_runtime';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TaskRuntime[]|TaskRuntime|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TaskRuntime|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
