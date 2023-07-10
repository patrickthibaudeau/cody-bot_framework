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

require_once('config.php');

global $CFG, $WS, $VIEW, $USER;

// If the session is not set, redirect to login.php
requires_login();

// Get logs from database

$sql = "Select
    c.id,
    c.prompt,
    c.request_id,
    c.content,
    c.machine,
    c.failed_responding,
    c.conversation_id,
    From_UnixTime(c.created_at) As created_at,
    c.ip
From
    cody.logs c
Order By
    created_at Desc";

$logs = $DB->query($sql);


$data = [
    'user' => $USER,
    'logs' => $logs,
];

$bot_logs = $VIEW->loadTemplate('bot_logs');
echo $bot_logs->render($data);

//print_object($spaces);



