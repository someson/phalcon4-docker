<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Cache\{ AdapterFactory, CacheFactory };
use Phalcon\Storage\SerializerFactory;
use Library\Traits\TraitFilesystem;
use Library\Env;

class Cache implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $config = $di->getShared('config');
        $cacheServices = $config->cache->toArray();
        foreach ($cacheServices as $serviceName => $config) {
            $di->set($serviceName, function() use ($config) {
                $cacheFactory = new CacheFactory(new AdapterFactory(new SerializerFactory()));
                $cache = $cacheFactory->load($config);
                $dirCreated = false;
                if (isset($options['adapter']) && $options['adapter'] === 'stream') {
                    $dirCreated = TraitFilesystem::checkOrCreate($config['options']['storageDir']);
                }
                if ($dirCreated && ! Env::isProduction()) {
                    $cache->clear();
                }
                return $cache;
            }, $options['shared'] ?? true);
        }
    }
}
