<?php

namespace Library\Cli;

class Execute
{
    /** @var Execute */
    protected static $_instance;

    /** @var array */
    protected $_command;

    private function __construct()
    {
        $this->_command = [];
    }

    public static function singleton(): self
    {
        return self::$_instance ?? new self();
    }

    /**
     * execute a command
     * @param string $cmd
     * @param string $file
     * @param int $line
     * @param resource|string $stdin Readable resource stream or string to be passed to proc STDIN
     * @return true|int return exit code of command
     */
    public function execute($cmd, $file, $line, $stdin = null)
    {
        // Create temporary files to write output/stderr (dont worry about stdin)
        $outFile = tempnam('.', 'cli');
        $errFile = tempnam('.', 'cli');

        // Map Files to Process's output, input, error to temporary files
        $descriptor = [
            ['pipe', 'r'],
            ['file', $outFile, 'w'],
            ['file', $errFile, 'w'],
        ];

        $start = microtime(true);
        $proc = proc_open($cmd, $descriptor, $pipes);
        if (\is_resource($proc)) {
            if ($stdin) {
                if (\is_resource($stdin)) {
                    stream_copy_to_stream($stdin, $pipes[0]);
                } else {
                    fwrite($pipes[0], $stdin);
                }
            }
            fclose($pipes[0]);
            $return = proc_close($proc);
        } else {
            $return = 255;
        }
        $end = microtime(true);

        @unlink($outFile);
        @unlink($errFile);

        $command = new Command;
        $command->command = $cmd;
        $command->file = $file;
        $command->line = $line;
        $command->result_code = $return;
        $command->success = $return === 0;
        $command->stdout = file_get_contents($outFile);
        $command->stderr = file_get_contents($errFile);
        $command->time = ($end - $start);

        $this->_command[] = $command;

        return $return === 0 ? true : $return;
    }

    /**
     * Get all commands executed
     * @return array of executed commands
     */
    public function getCommands(): array
    {
        return $this->_command;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        /** @var string $commands */
        $commands = print_r($this->_command, true);
        return \PHP_SAPI === 'cli' ? $commands : sprintf('<pre>%s</pre>', $commands);
    }
}
