<?php

namespace Library\Cli;

class PhpError
{
    /**
     * @param int $errNo
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     * @return bool
     */
    public static function errorHandler($errNo, $errStr, $errFile, $errLine): bool
    {
        if ((int) $errNo !== E_STRICT) {
            $entry = new Models\TaskRuntime([
                'title' => $errStr,
                'file' => $errFile,
                'line' => $errLine,
                'error_type' => $errNo,
                'server_name' => php_uname('n'),
                'execution_script' => $_SERVER['PHP_SELF'],
                'pid' => getmypid(),
            ]);
            return $entry->save();
        }
        return false;
    }

    public static function runtimeShutdown(): void
    {
        if (!empty($e = error_get_last())) {
            self::errorHandler($e['type'], $e['message'], $e['file'], $e['line']);
        }
    }
}
