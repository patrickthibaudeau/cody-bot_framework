<?php
// This file is part of Cody AI Bot Framework - https://github.com/patrickthibaudeau/cody-bot_framework
//
// Cody AI Bot Framework is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Cody AI Bot Framework is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Cody AI Bot Framework.  If not, see <http://www.gnu.org/licenses/>.

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
$CFG->dataroot ='/path/to/data/folder'; // Keep folder outside of web root. Must be writeable by www-data

// New webservice
$WS = new webservice();
//API Key
$CFG->api_key = '';
$CFG->bot_id = '';
$CFG->conversation_id = '';
$CFG->api_url = 'https://getcody.ai/api/v1'; //No trailing slash

// Initialize Mustache Templating and create VIEW global object
Mustache_Autoloader::register();

$VIEW = new Mustache_Engine(array(
    'template_class_prefix' => '__CODYTemplates_',
    'cache' => $CFG->dataroot . '/cache/mustache',
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

require ((__DIR__) . '/lib.php');