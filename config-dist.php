<?php
require_once((__DIR__) . '/classes/db.class.php');
require ((__DIR__) . '/classes/Mustache/Autoloader.php');
require ((__DIR__) . '/classes/webservice.php');

use yorku\webservice;

unset($CFG);
global $CFG, $WS, $VIEW, $DB;

session_start();

$CFG = new stdClass();
// Root directory
$CFG->dirroot = (__DIR__);
// Database configuration
$CFG->dbhost = '';
$CFG->dbname = '';
$CFG->dbuser = '';
$CFG->dbpass = '';

// Global Database
$DB = new MeekroDB($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

// Web host
$CFG->wwwroot = 'https://your.domain.url'; // no trailing slash

// New webservice
$WS = new webservice();
//API Key
$CFG->api_key = '';

// Initialize Mustache Templating and create VIEW global object
Mustache_Autoloader::register();

$VIEW = new Mustache_Engine(array(
    'template_class_prefix' => '__CODYTemplates_',
    'cache' => dirname(__FILE__) . '/tmp/cache/mustache',
    'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
    'cache_lambda_templates' => false,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
    'escape' => function ($value) {
        if (!is_array($value)) {
            return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        }
    },
    'charset' => 'ISO-8859-1',
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
    'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
));
/**
 * @param $object
 * @return void
 */
function print_object($object)
{
    echo '<pre>';
    print_r($object);
    echo '</pre>';
}