<?php

namespace Library\Cli;

class Output
{
    public const COLOR_NONE = "\e[0m";
    public const COLOR_RED = "\e[0;31m";
    public const COLOR_LIGHT_RED = "\e[0;91m";
    public const COLOR_YELLOW = "\e[1;33m";
    public const COLOR_LIGHT_YELLOW = "\e[1;93m";
    public const COLOR_GREEN = "\e[0;32m";
    public const COLOR_LIGHT_GREEN = "\e[0;92m";
    public const COLOR_BLUE = "\e[0;34m";
    public const COLOR_LIGHT_CYAN = "\e[0;96m";

    /** @var string output sent to standard error */
    protected static $_stderr;

    /** @var string output sent to standard output */
    protected static $_stdout;

    public static function stderr($msg): void
    {
        fwrite(STDERR, self::COLOR_LIGHT_RED . $msg . self::COLOR_NONE . PHP_EOL);
        self::$_stderr .= $msg . PHP_EOL;
    }

    public static function debug($msg): void
    {
        self::console($msg, self::COLOR_YELLOW);
        self::$_stdout .= $msg . PHP_EOL;
    }

    public static function stdout($msg, $color = self::COLOR_NONE): void
    {
        self::console($msg, $color);
        self::$_stdout .= $msg . PHP_EOL;
    }

    public static function console($msg, $color = self::COLOR_NONE): void
    {
        fwrite(STDOUT, $color . $msg . self::COLOR_NONE . PHP_EOL);
    }

    /**
     * get all standard output text
     * @return string|null
     */
    public static function getStdout(): ?string
    {
        return self::$_stdout;
    }

    /**
     * get all standard error text
     * @return string|null
     */
    public static function getStderr(): ?string
    {
        return self::$_stderr;
    }

    public static function clear(): void
    {
        self::$_stderr = '';
        self::$_stdout = '';
    }

    public static function cursor(): Cursor
    {
        return new Cursor();
    }
}
