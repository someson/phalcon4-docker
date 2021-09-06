<?php

namespace Library\Traits;

trait TraitFilesystem
{
    /**
     * @param string|null $storageDir
     * @return bool
     */
    public static function checkOrCreate(?string $storageDir = null): bool
    {
        if (! $storageDir) {
            throw new \InvalidArgumentException('Argument [storageDir] must be defined');
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
