<?php
require_once('../config.php');
global $CFG;
session_unset();
header('Location: '. $CFG->wwwroot . '/login/');