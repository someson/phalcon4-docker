<?php

namespace Library\Cli;

use Phalcon\Events\{ Event, Manager };

class Debug extends Manager
{
    public function __construct($enable = true)
    {
        if ($enable === true) {
            $this->attach('console:afterHandleTask', function(Event $event, $console) {
                $this->display($console);
            });
        }
    }

    public function display(Application $console)
    {
        $dispatcher = $console->getDI()->getShared('dispatcher');
        $taskName = $dispatcher->getTaskName();
        $actionName = $dispatcher->getActionName();

        $curMem = memory_get_usage(false);
        $curRealMem = memory_get_usage(true);
        $peakMem = memory_get_peak_usage(false);
        $peakRealMem = memory_get_peak_usage(true);

        $totalTime = microtime(true) - $_SERVER['REQUEST_TIME'];
        $startTime = date('d.m.Y H:i:s', $_SERVER['REQUEST_TIME']);

        Output::stdout('');
        Output::stdout(Output::COLOR_BLUE . '--------------- DEBUG ENABLED ---------------' . Output::COLOR_NONE);
        Output::stdout(Output::COLOR_BLUE . '+++ Overview +++' . Output::COLOR_NONE);
        Output::stdout('task: ' . $taskName);
        Output::stdout('action: ' . $actionName);
        Output::stdout(sprintf('total time: %s start time: %s end time: %s', $totalTime, $startTime, date('d.m.Y H:i:s')));
        if ($console->isRecording()) {
            Output::stdout('task id: ' . $console->getTaskId());
        }
        Output::stdout('hostname: ' . php_uname('n'));
        Output::stdout('pid: ' . getmypid());

        if ($console->isSingleInstance()) {
            Output::stdout('pid file: ' . Pid::singleton('')->getFileName());
        }

        Output::stdout('');
        Output::stdout(sprintf('current memory: %s bytes %s kbytes', $curMem, round($curMem / 1024, 2)));
        Output::stdout('current real memory: $curRealMem bytes ' . round($curRealMem / 1024, 2) . ' kbytes');
        Output::stdout('peak memory: $peakMem bytes ' . round($peakMem / 1024, 2) . ' kbytes');
        Output::stdout('peak real memory: $peakRealMem bytes ' . round($peakRealMem / 1024, 2) . ' kbytes');
        Output::stdout('');

        // Print out Commands
        $commands = Execute::singleton()->getCommands();
        if (!empty($commands)) {
            Output::stdout(Output::COLOR_BLUE . '+++ Cli Commands +++' . Output::COLOR_NONE);
            foreach ($commands as $command) {
                $result = $command->success ? Output::COLOR_GREEN . 'Success' . Output::COLOR_NONE : Output::COLOR_RED . 'Failed' . Output::COLOR_NONE;
                Output::stdout($command->command);
                Output::stdout($command->file . "\t" . $command->line . "\t" . $result);
                Output::stdout('');
            }
            Output::stdout('');
        }

        // Print out Queries
        if ($console->getDI()->has('profiler')) {
            Output::stdout(Output::COLOR_BLUE . '+++ Queries +++' . Output::COLOR_NONE);
            $profiles = $console->getDI()->getShared('profiler')->getProfiles();
            if (!empty($profiles)) {
                foreach ($profiles as $profile) {
                    Output::stdout($profile->getSQLStatement());
                    Output::stdout('time: ' . $profile->getTotalElapsedSeconds());
                    Output::stdout('');
                }
                Output::stdout('');
            }
        }

        // Print out Exceptions
        /*$exceptions = Logger::getInstance()->getAll();
        if (!empty($exceptions)) {
            Output::stdout(Output::COLOR_BLUE . "+++Exceptions+++" . Output::COLOR_NONE);
            foreach($exceptions as $except) {
                Output::stdout($except->getMessage());
                Output::stdout($except->getCode() . "\t" . $except->getFile() . "\t" . $except->getLine());
                Output::stdout("");
            }
            Output::stdout("");
        }*/

        // Print out all included php files
        $files = get_required_files();
        Output::stdout(Output::COLOR_BLUE . '+++ Included Files +++' . Output::COLOR_NONE);
        foreach ($files as $file) {
            Output::stdout($file);
        }
        Output::stdout('');
    }
}
