<?php
// Test bootstrap for CakePHP plugin
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Core\Plugin;

// Define ROOT & DS similar to app context
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Temp directories
$baseTmp = sys_get_temp_dir() . DS . 'iserter_postman_' . uniqid();
$dirs = ['tmp', 'logs', 'cache'];
foreach ($dirs as $d) {
    $path = $baseTmp . DS . $d;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}
if (!defined('TMP')) {
    define('TMP', $baseTmp . DS . 'tmp' . DS);
}
if (!defined('CACHE')) {
    define('CACHE', $baseTmp . DS . 'cache' . DS);
}
if (!defined('LOGS')) {
    define('LOGS', $baseTmp . DS . 'logs' . DS);
}

require_once ROOT . DS . 'vendor' . DS . 'autoload.php';

Configure::write('debug', true);
Configure::write('App.namespace', 'TestApp');

// Ensure Router is clean each run
Router::reload();

// Set a default baseUrl variable for Postman builder expectations
Configure::write('Postman.baseUrl', 'http://localhost');
// Ensure a clean route collection before each test suite run
Router::resetRoutes();
// Integration app scaffold removed; unit tests use fakes.
