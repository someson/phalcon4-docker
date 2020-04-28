<?php

namespace App;

use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Library\Cli\{ Output, Application };
use Library\Cli\Listeners\TaskListener;
use App\Modules\Cli\Module as CliModule;

class CliApplication extends Application
{
    public function __construct(DiInterface $di)
    {
        $this->registerModules([
            'cli' => [
                'className' => CliModule::class,
            ],
        ]);
        parent::__construct($di);
    }

    public function registerServices(Di $di): void
    {
        foreach ($this->getProviders() as $provider) {
            $di->register(new $provider());
        }
    }

    public function getProviders(): array
    {
        return [
            Providers\CliRouter::class,
            Providers\DatabaseCli::class, // + dbCli
            Providers\EventsManager::class,
            Providers\Filesystem::class,
            Providers\Google::class,
            Providers\Logger::class, // + loggerSql
            Providers\Mail::class,
            Providers\ModelsManager::class,
            Providers\ModelsMetadata::class,
            Providers\SimpleView::class,
            Providers\MailView::class,
            Providers\Transaction::class,
            Providers\Url::class, // + slug
            Providers\Aws::class,
            Providers\Cloudinary::class,
            Providers\Pipedrive::class,
            Providers\Deepl::class
        ];
    }

    public function handle(array $arguments = null)
    {
        /** @var \Phalcon\Events\Manager $eventsManager */
        $eventsManager = $this->getDI()->getShared('eventsManager');
        $eventsManager->attach('console', new TaskListener());
        $this->setEventsManager($eventsManager);

        parent::handle($arguments);
    }

    public function handleException(\Throwable $e): void
    {
        $sub = '%s[ERROR]%s %s. [File %s, Line %u]';
        $msg = sprintf($sub, Output::COLOR_RED, Output::COLOR_NONE, $e->getMessage(), $e->getFile(), $e->getLine());
        Output::stderr($msg);
    }
}
