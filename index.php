<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/14/13
 * @Date Modified: 11/26/13
 * @Purpose: Front Controller
 * @Version: 1.0
 *
 * Front controller for example.com
 */

/**
 * Project Environment
 *
 * "dev" for Development
 * "pro" for Productions
 */
define('__PROJECT_ENVIRONMENT', 'dev');

/**
 * Define the base path to the framework
 * Should look something like /var/www/framework (i.e. the framework *SHOULD NOT* be in a web accessible directory)
 * This is the only file that should be web accessible (aside from assets, like js/css/images)
 */
define('__BASE_PATH', DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'kenyon.sexypenguins.com' . DIRECTORY_SEPARATOR . 'tnbtimeclock' . DIRECTORY_SEPARATOR);

/**
 * Define the path to the system directory
 */
define('__SYSTEM_PATH', __BASE_PATH . 'system' . DIRECTORY_SEPARATOR);

/**
 * Define the path to the configuration directory
 */
define('__CONFIG_PATH', __BASE_PATH . 'configuration' . DIRECTORY_SEPARATOR);


/**
 * Define the path to the plugins directory
 */
define('__PLUGINS_PATH', __BASE_PATH . 'plugins' . DIRECTORY_SEPARATOR);

/**
 * Define the path to the applications directory
 */
define('__APPLICATIONS_PATH', __BASE_PATH . 'applications' . DIRECTORY_SEPARATOR);

/**
 * Define the site path
 */
define('__SITE_PATH', __APPLICATIONS_PATH . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR);

/**
 * Figure out if error reporting should be turned on or off
 */
if ('dev' === __PROJECT_ENVIRONMENT) {
   ini_set('display_errors', true);
} else {
   ini_set('display_errors', false);
}

/**
 * Set the includes path
 */
ini_set('include_path', __SYSTEM_PATH);

require_once 'functions.php';

/**
 * Initialize dependencyInjection
 */
$system_di = dependencyInjection::initialize();


/**
 * Initialize site configuration
 */
configuration::set_file($_SERVER['HTTP_HOST']);
$system_di->config = configuration::initialize();

/**
 * Initialize error handling
 */
set_error_handler(array('error', 'handle_error'));
$system_di->error = new error($system_di);
/**
 * Initialize sessions
 */
session_start();
$system_di->session = &$_SESSION;

/**
 * Load the database handler
 */
$system_di->db = new db($system_di);

/**
 * Initialize the router
 */
$system_di->router = new router($system_di);
/**
 * Initialize controller path
 */
$system_di->router->controller_path(__SITE_PATH . 'controller');

/**
 * Initialize the template
 */
$system_di->template = new template($system_di);

/**
 * Load the route
 */
$system_di->router->load_route();

//End File
