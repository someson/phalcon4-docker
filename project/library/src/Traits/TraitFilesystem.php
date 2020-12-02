<?php

namespace Library\Traits;

trait TraitFilesystem
{
    /**
     * @param string $storageDir
     * @return bool
     * @throws \RuntimeException
     */
    public static function checkOrCreate(?string $storageDir = null): bool
    {
        if (! $storageDir) {
            new \InvalidArgumentException('Argument [storageDir] must be defined');
        }
        if (is_writable($storageDir)) {
            return true;
        }
        if (! @mkdir($storageDir, 0777, true) && ! is_dir($storageDir)) {
            throw new \RuntimeException(sprintf('[%s] could not be created', $storageDir));
        }
        return true;
    }
}
