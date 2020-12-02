<?php

namespace Library\Cli;

/**
 * Class Cursor
 * @package Library\Cli
 * @see http://ascii-table.com/ansi-escape-sequences.php
 */
class Cursor
{
    public function up(int $units = 1)
    {
        fwrite(\STDOUT, sprintf("\e[%uA", $units));
        return $this;
    }

    public function down(int $units = 1)
    {
        fwrite(\STDOUT, sprintf("\e[%uB", $units));
        return $this;
    }

    public function forward(int $units = 1)
    {
        fwrite(\STDOUT, sprintf("\e[%uC", $units));
        return $this;
    }

    public function backward(int $units = 1)
    {
        fwrite(\STDOUT, sprintf("\e[%uD", $units));
        return $this;
    }
}
