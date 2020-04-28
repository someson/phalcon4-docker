<?php

namespace Library\Traits;

trait TraitConfigurable
{
    protected array $_o = [];

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $key, $value): self
    {
        $this->_o[$key] = $value;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addOptions(array $options): self
    {
        $this->_o = array_merge($this->_o, $options);
        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getOption(string $key)
    {
        return $this->_o[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->_o;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasOption(string $key): bool
    {
        return isset($this->_o[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function isOption(string $key, $value): bool
    {
        return $this->hasOption($key) && $this->getOption($key) === $value;
    }
}
