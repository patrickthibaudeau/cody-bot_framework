<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

global $CFG, $WS, $VIEW;

$data = [];
$page = $VIEW->loadTemplate('index');
echo $page->render($data);

//print_object($spaces);



