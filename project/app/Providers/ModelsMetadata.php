<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Mvc\Model\MetaData\{ Memory, Stream };
use Phalcon\Storage\SerializerFactory;
use Library\Traits\TraitFilesystem;
use Library\Env;

class ModelsMetadata implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'modelsMetadata';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var \Phalcon\Di $this */
            $config = $this->getShared('config');
            if ($metaDataConfig = $config->path('database.metaDataCache')) {
                $serviceConfig = $metaDataConfig && isset($metaDataConfig['adapter']) ? $metaDataConfig->toArray() : [
                    'adapter' => 'stream',
                    'options' => [
                        'prefix' => CURRENT_APP . '-',
                        'metaDataDir' => CACHE_DIR . DS . 'meta' . DS,
                    ],
                ];
                if (Env::isProduction()) {
                    $options = $serviceConfig['options'] ?? [];
                    if (isset($serviceConfig['adapter']) && $serviceConfig['adapter'] === 'stream') {
                        TraitFilesystem::checkOrCreate($serviceConfig['options']['metaDataDir'] ?? null);
                        return new Stream($options);
                    }
                    $adapter = '\\Phalcon\\Mvc\\Model\\MetaData\\' . ucfirst($serviceConfig['adapter']);
                    return new $adapter(new AdapterFactory(new SerializerFactory()), $options);
                }
            }
            $metaData = new Memory(['lifetime' => 0]);
            $metaData->reset();

            return $metaData;
        });
    }
}
