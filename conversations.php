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
if (isset($_GET['id'])) {
    $bot_id = $_GET['id'];
} else if (isset($_GET['bot_id'])) {
    $bot_id = $_GET['bot_id'];
} else {
    redirect($CFG->wwwroot . '/bots.php');
}

$headers = $WS->get_headers($CFG->api_key);
// Get Bots
$conversations = $WS->send_curl_request(
    'GET',
    $headers,
    $CFG->api_url . '/conversations/'
);

$conversations = json_decode($conversations);

$bot_conversations = [];
foreach ($conversations->data as $conversation) {
    if ($conversation->bot_id == $bot_id) {
        $bot_conversations[] = $conversation;
    }
}

$data = [
    'bot_id' => $bot_id,
    'conversations' => $bot_conversations,
];

$conversations = $VIEW->loadTemplate('conversations');
echo $conversations->render($data);

//print_object($spaces);



