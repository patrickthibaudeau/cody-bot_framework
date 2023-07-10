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

global $CFG, $WS, $VIEW, $DB;

// Get
$prompt = $_REQUEST['prompt'];
$headers = $WS->get_headers($CFG->api_key,'POST');

// Get client IP
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$content = '{"content": "' . $prompt . '","conversation_id":"' . $CFG->conversation_id . '"}';
$message = $WS->send_curl_request(
    'POST',
    $headers,
    $CFG->api_url . '/messages',
    $content
);

$message = json_decode($message);

$log_data = [
    'prompt' => $prompt,
    'request_id' => $message->data->id,
    'content' => $message->data->content,
    'machine' => $message->data->machine,
    'failed_responding' => $message->data->failed_responding,
    'conversation_id' => $message->data->conversation_id,
    'created_at' => $message->data->created_at,
    'ip' => $ip,
];

$DB->insert('logs', $log_data);

// Clean up content string
$content = $message->data->content;
$content = str_replace('\/', '/', $message->data->content);
$content = preg_replace('/according to the knowledge base/', '', $content);
$content = preg_replace('/According to the information provided in the knowledge base, /', '', $content);
$content = preg_replace('/Based on the information provided in the knowledge base, /', '', $content);

$content = nl2br( htmlspecialchars( $content));
$content = make_link($content);
$content = make_email($content);

// Add content back to message object
$message->data->content = $content;
echo json_encode($message);

// take a string and turn any valid URLs into HTML links
function make_link($input) {
    $url_pattern = '<https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)>';
    $str = preg_replace($url_pattern, '<a href="$0" target="_blank">$0</a> ', $input);
    // Remove duplicate links
    return preg_replace('/\[.*\]/', '', $str);
}

function make_email($input){
    //Detect and create email
    $mail_pattern = "/([A-z0-9\._-]+\@[A-z0-9_-]+\.)([A-z0-9\_\-\.]{1,}[A-z])/";
    return preg_replace($mail_pattern, '<a href="mailto:$1$2">$1$2</a>', $input);

}
