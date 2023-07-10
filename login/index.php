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

require_once('../config.php');

global $CFG, $WS, $VIEW, $DB, $USER;
// If the session is not set, show the login page
if (!isset($_SESSION['CODYBOT_USER'])) {
    // If the form has been submitted, check the username and password
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        // Get user
        $sql = "SELECT * FROM user WHERE username = %s_username AND password = %s_password";
        $user = $DB->query($sql, ['username' => $username, 'password' => $password]);
        // If a user is found, set the session and redirect to bots.php
        if (isset($user[0])) {
            $_SESSION['CODYBOT_USER'] = session_id();
            $USER = (object)$user[0];
            redirect($CFG->wwwroot . '/bots.php');
        } else {
            // If no user is found, show the login page with an error
            $data = [
                'error' => 'Invalid username or password'
            ];
            $page = $VIEW->loadTemplate('login');
            echo $page->render($data);
        }
    } else {
        // Show the login page
        $data = [];
        $page = $VIEW->loadTemplate('login');
        echo $page->render($data);
    }
} else {
    // User already logged in, redirect to bots.php
    header('Location: '. $CFG->wwwroot . '/bots.php');
}






