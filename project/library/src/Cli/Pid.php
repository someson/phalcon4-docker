<?php

namespace Library\Cli;

class Pid
{
    protected $_fp;

    /** @var string */
    protected $_pidFile;

    /** @var bool */
    protected $_isCreated, $_isRemoved;

    public function __construct($filePath)
    {
        $this->_pidFile = $filePath;
        $this->_isCreated = false;
        $this->_isRemoved = false;
    }

    public function remove(): bool
    {
        if ($this->_isCreated) {
            fclose($this->_fp);
            if (unlink($this->_pidFile)) {
                return $this->_isRemoved = true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    public function create(): bool
    {
        if ($this->exists()) {
            throw new \RuntimeException('Instance is already running.');
        }
        $this->_fp = fopen($this->_pidFile, 'xb');
        if (! $this->_fp) {
            return false;
        }
        if (! flock($this->_fp, LOCK_EX | LOCK_NB)) {
            fclose($this->_fp);
            return false;
        }
        fwrite($this->_fp, getmypid());
        return $this->_isCreated = true;
    }

    public function exists(): bool
    {
        return file_exists($this->_pidFile);
    }

    public function created(): bool
    {
        return $this->_isCreated;
    }

    public function removed(): bool
    {
        return $this->_isRemoved;
    }

    public function getFileName(): string
    {
        return $this->_pidFile;
    }
}
