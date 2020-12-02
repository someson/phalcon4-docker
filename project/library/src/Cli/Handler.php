<?php

namespace Library\Cli;

use Library\Cli\Models\Task;

class Handler
{
    /** @var Pid */
    protected $_pid;

    /** @var bool */
    protected $_isDebug, $_isRecording, $_isSingleInstance;

    /** @var string */
    protected $_task, $_action;

    /** @var int */
    protected $_taskId;

    public function __construct()
    {
        $this->_isDebug = $this->_isSingleInstance = $this->_isRecording = false;
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    public function startTask(): bool
    {
        if ($this->isSingleInstance()) {
            $fileName = sprintf('%s-%s.pid', $this->_task, $this->_action);
            $this->_pid = new Pid(TMP_DIR . DS . 'console' . DS . $fileName);
            if ($this->_pid->exists()) {
                throw new \RuntimeException('Instance of task is already running.', 999);
            }
            if (! $this->_pid->create()) {
                throw new \RuntimeException('Unable to create pid file.');
            }
            if ($this->isDebug()) {
                Output::console(sprintf('[DEBUG] Created Pid File: %s', $this->getPidFile()));
            }
        }
        if ($this->isRecording()) {
            $task = new Task();
            $taskId = $task->insertTask($_SERVER['PHP_SELF'], $this->_task, $this->_action);
            $this->setTaskId($taskId);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function finishTask(): bool
    {
        if ($this->isSingleInstance()) {
            $errorPrefix = Output::COLOR_RED . '[ERROR]' . Output::COLOR_NONE;
            if ($this->_pid instanceof Pid && $this->_pid->created() && !$this->_pid->removed()) {
                $result = $this->_pid->remove();
                if ($this->isDebug()) {
                    $result ?
                        Output::console(sprintf('[DEBUG] Removed Pid File: %s', $this->getPidFile())) :
                        Output::stderr(sprintf('%s Failed to remove Pid File: %s', $errorPrefix, $this->getPidFile()));
                }
            } else {
                Output::stderr(sprintf('%s Failed to remove Pid file : File not found.', $errorPrefix));
            }
        }
        if ($this->isRecording()) {
            Task::updateById($this->getTaskId(), Output::getStdout(), Output::getStderr());
        }
        return true;
    }

    public function setTask(string $taskName)
    {
        $this->_task = $taskName;
        return $this;
    }

    public function setAction(string $actionName)
    {
        $this->_action = $actionName;
        return $this;
    }

    public function setDebug($debug)
    {
        if ($this->_isDebug = (bool) $debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
        return $this;
    }

    public function isDebug(): bool
    {
        return $this->_isDebug;
    }

    public function setRecording($record)
    {
        $this->_isRecording = (bool) $record;
        return $this;
    }

    public function isRecording(): bool
    {
        return $this->_isRecording;
    }

    public function setSingleInstance($single)
    {
        $this->_isSingleInstance = $single;
        return $this;
    }

    public function isSingleInstance(): bool
    {
        return $this->_isSingleInstance;
    }

    public function getPidFile(): ?string
    {
        return $this->_pid ? $this->_pid->getFileName() : null;
    }

    public function getTaskId(): int
    {
        return $this->_taskId;
    }

    public function setTaskId($id)
    {
        $this->_taskId = $id;
        return $this;
    }
}
