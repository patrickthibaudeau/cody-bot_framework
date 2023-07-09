<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

global $CFG, $WS, $VIEW;
$bot_id = $_GET['id'];


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
    'conversations' => $bot_conversations,
];

$conversations = $VIEW->loadTemplate('conversations');
echo $conversations->render($data);

//print_object($spaces);



