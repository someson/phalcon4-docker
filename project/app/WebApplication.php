<?php

namespace App;

use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Application;
use Library\{ Debug, Env };

class WebApplication extends Application
{
    public function __construct(DiInterface $di)
    {
        $this->registerModules([
            'frontend' => [
                'className' => Modules\Frontend\Module::class,
                'routes' => Modules\Frontend\Routes::class,
            ],
        ]);
        parent::__construct($di);
    }

    public function getProviders(): array
    {
        return [
            Providers\Config::class,
            Providers\EventsManager::class,
            Providers\Cache::class,
            Providers\Cookies::class,
            Providers\Crypt::class,
            Providers\Database::class,
            Providers\Filter::class,
            Providers\Flash::class,
            Providers\Logger::class,
            Providers\ModelsMetadata::class,
            Providers\Router::class,
            Providers\Security::class,
            Providers\Session::class,
            Providers\View::class,
            Providers\Tag::class,
            Providers\Url::class,
        ];
    }

    public function registerServices(Di $di): void
    {
        foreach ($this->getProviders() as $provider) {
            $di->register(new $provider());
        }
    }

    public function handle(string $uri)
    {
        if ($api = (stripos(trim($uri, '/ '), 'api/') === 0)) {
            $this->useImplicitView(false);
        }
        $response = parent::handle($uri);
        if (! $api) {
            echo $response->getContent();
        }
    }

    public function handleException(\Throwable $e)
    {
        if (ob_get_level()) {
            ob_end_clean();
        }
        if (Env::isDevelopment()) {
            /** @var \Library\Mvc\Dispatcher $dispatcher */
            $dispatcher = $this->getDI()->getShared('dispatcher');
            if (method_exists($dispatcher, 'setOption')) {
                $dispatcher->setOption('exceptionData', [
                    'class' => \get_class($e),
                    'message' => $e->getMessage(),
                ]);
            }
            return (new Debug())->listen($exceptions = true, $errors = true)->onUncaughtException($e);
        }
        require_once 'Micro.php';
    }
}
