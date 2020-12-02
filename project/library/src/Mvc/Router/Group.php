<?php

namespace Library\Mvc\Router;

use Phalcon\Mvc\Router\Group as GroupBase;
use Phalcon\Mvc\Router\{ GroupInterface, RouteInterface };

class Group extends GroupBase
{
    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @param mixed $httpMethods
     * @return Mock|RouteInterface
     */
    public function add($pattern, $paths = null, $httpMethods = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, $httpMethods);
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addGet($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'GET');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addPost($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'POST');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addPut($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'PUT');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addPatch($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'PATCH');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addDelete($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'DELETE');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addOptions($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'OPTIONS');
    }

    /**
     * @param mixed $pattern
     * @param mixed $paths
     * @return Mock|RouteInterface
     */
    public function addHead($pattern, $paths = null): RouteInterface
    {
        return $this->_addRoute($pattern, $paths, 'HEAD');
    }

    /**
     * @param string|array $pattern
     * @param mixed $paths
     * @param mixed $httpMethods
     * @return Mock|RouteInterface
     */
    protected function _addRoute($pattern, $paths = null, $httpMethods = null): RouteInterface
    {
        if (\is_array($pattern)) {
            if (! isset($pattern[CURRENT_TLD])) {
                return new Mock();
            }
            return $this->addRoute($pattern[CURRENT_TLD], $paths, $httpMethods);
        }
        return $this->addRoute($pattern, $paths, $httpMethods);
    }

    /**
     * @param string|array $prefix
     * @return GroupInterface
     */
    public function setPrefix($prefix): GroupInterface
    {
        if (\is_array($prefix)) {
            return parent::setPrefix($prefix[CURRENT_TLD]);
        }
        return parent::setPrefix($prefix);
    }
}
