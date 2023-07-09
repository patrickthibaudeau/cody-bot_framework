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

include_once('config.php');

global $CFG, $WS, $VIEW;
$conversation_id = $_GET['id'];
$bot_id = $_GET['bot_id'];

$headers = $WS->get_headers($CFG->api_key);

//Delete conversation
$delete_conversation = $WS->send_curl_request(
    'DELETE',
    $headers,
    $CFG->api_url . '/conversations/' . $conversation_id
);

