<?php
// Application name
define('APPLICATION', 'admin');

// Paths
define('ROOT_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', ROOT_PATH . APPLICATION . DIRECTORY_SEPARATOR);
define('MODULES_PATH', ROOT_PATH . 'modules' . DIRECTORY_SEPARATOR);

// Database connection
@include_once CORE_PATH . 'Configuration.php';

// Application URL
define('URL', ROOT_URL . 'admin/');

// Application configuration
define('USE_DATABASE', true);
define('REQUIRES_LOGIN', true);
define('DYNAMIC_MENU', true);
define('USE_MODULES', true);
define('DEFAULT_MODULE', false);
define('DEFAULT_CONTROLLER', 'records');
define('DEFAULT_METHOD', 'list');
define('MODULE_URL_KEYWORD', 'module');
$lang = 'en_US';

// Core files
require_once CORE_PATH . 'Application.php';
require_once CORE_PATH . 'Constants.php';
require_once CORE_PATH . 'Functions.php';
require_once CORE_PATH . 'Request.php';
require_once CORE_PATH . 'Bootstrap.php';
require_once CORE_PATH . 'Controller.php';
require_once CORE_PATH . 'Model.php';
require_once CORE_PATH . 'View.php';
require_once CORE_PATH . 'Database.php';
require_once CORE_PATH . 'Session.php';
require_once CORE_PATH . 'Singleton.php';

// Run application
Application::run();
?>