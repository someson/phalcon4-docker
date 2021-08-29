<?php

namespace App;

use DomainException;
use InvalidArgumentException;
use Phalcon\Application\AbstractApplication;
use Phalcon\{ Di, Registry };
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Mvc\Model;

class Bootstrap
{
    /** @var AbstractApplication */
    private $_app;

    /**
     * Bootstrap constructor.
     * @param null $site Domain name for console applications
     * @param bool|true $autoDetect Used to force web app in case of testing
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function __construct($site = null, bool $autoDetect = true)
    {
        Model::setup(['castOnHydrate' => true]);
        Model::setup(['ignoreUnknownColumns' => true]);

        $domain = $this->identify($site, $autoDetect);
        $this->defineConstants($domain);

        $isCli = $autoDetect && $this->isCli();
        $di = $isCli ? new Cli : new FactoryDefault;
        Di::setDefault($di);

        $this->_app = $isCli ? new CliApplication($di) : new WebApplication($di);
        $this->_app->registerServices($di);

        $registry = new Registry();
        $registry->set('modules', $this->_app->getModules());
        $di->set('registry', $registry);
    }

    /**
     * @return CliApplication|WebApplication
     */
    public function getApplication()
    {
        return $this->_app;
    }

    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return !isset($_SERVER['SERVER_SOFTWARE'])
            && (\PHP_SAPI === 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0));
    }

    /**
     * @param null $site
     * @param bool $autoDetect
     * @return string
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function identify($site = null, $autoDetect = true): string
    {
        if (! $site && $this->isCli()) {
            throw new InvalidArgumentException('Console application could not define the platform.');
        }
        $domain = ($autoDetect && $this->isCli()) ? $site : ($_SERVER['SERVER_NAME'] ?? env('DEFAULT_DOMAIN'));
        $parts = explode('.', $domain);
        $tld = strtolower(end($parts));

        // if an IP requested
        if (is_numeric($tld)) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                throw new DomainException('Request a domain name instead of IP');
            }
            return env('DEFAULT_DOMAIN');
        }
        return $domain;
    }

    /**
     * @param string $domain
     */
    public function defineConstants(string $domain): void
    {
        $parts = explode('.', $domain);

        \defined('APP_ENV')     || \define('APP_ENV', env('APP_ENV'));
        \defined('CURRENT_APP') || \define('CURRENT_APP', $domain);
        \defined('CURRENT_TLD') || \define('CURRENT_TLD', strtolower(end($parts)));
    }
}
