<?php

namespace Library\Cli\Models\Base;

class Task extends \Library\Models\ModelBase
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
    public $script_name;

    /**
     *
     * @var string
     */
    public $params;

    /**
     *
     * @var string
     */
    public $task_name;

    /**
     *
     * @var string
     */
    public $action_name;

    /**
     *
     * @var string
     */
    public $server_name;

    /**
     *
     * @var string
     */
    public $server_user;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $stop_time;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var integer
     */
    public $exit_status;

    /**
     *
     * @var string
     */
    public $stdout;

    /**
     *
     * @var string
     */
    public $stderr;

    /**
     *
     * @var integer
     */
    public $pid;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("task");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'task';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task[]|Task|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
