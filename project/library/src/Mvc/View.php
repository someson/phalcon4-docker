<?php

namespace Library\Mvc;

use Phalcon\Mvc\View as PhalconView;

class View extends PhalconView
{
    /**
     * Make it possible to append new Dirs into the array of predefined Dirs. Result is an unique list
     * @param array|string $directory
     * @return $this
     */
    public function addViewsDir($directory): self
    {
        $dirs = (array) $directory;
        $knownDirs = (array) $this->getViewsDir();
        foreach ($dirs as $dir) {
            if (\is_string($dir)) {
                $normalized = rtrim($dir, '\\/'); // sync the difference between WIN and LINUX
                if (! \in_array($normalized . '/', $knownDirs, true)) { // Phalcon BUG: DIRECTORY_SEPARATOR for WIN 64 is "/"
                    $knownDirs[] = $normalized;
                }
            }
        }
        $this->setViewsDir($knownDirs);
        return $this;
    }

    /**
     * @return bool
     */
    public function isPicked(): bool
    {
        return \is_array($this->pickView) && $this->pickView[0];
    }
}
