<?php

/**
 * This file is part of the Phalcon Developer Tools.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

defined('PTOOLS_IP') || define('PTOOLS_IP', $_SERVER['REMOTE_ADDR']);
defined('BASE_PATH') || define('BASE_PATH', dirname(__FILE__, 2));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');

/**
 * @const ENV_PRODUCTION Application production stage.
 */
defined('ENV_PRODUCTION') || define('ENV_PRODUCTION', 'production');

/**
 * @const ENV_STAGING Application staging stage.
 */
defined('ENV_STAGING') || define('ENV_STAGING', 'staging');

/**
 * @const ENV_DEVELOPMENT Application development stage.
 */
defined('ENV_DEVELOPMENT') || define('ENV_DEVELOPMENT', 'development');

/**
 * @const ENV_TESTING Application test stage.
 */
defined('ENV_TESTING') || define('ENV_TESTING', 'testing');

/**
 * @const APPLICATION_ENV Current application stage.
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: ENV_DEVELOPMENT);

// ---------------------------- DO NOT EDIT BELOW ------------------------------

/**
 * @const PTOOLSPATH The path to the Phalcon Developers Tools.
 */
defined('PTOOLSPATH') || define('PTOOLSPATH', '/var/www/html/vendor/phalcon/devtools');
