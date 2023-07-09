<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// get url from content
preg_match('/http(.*)\/\/\S+/', $content, $match);
if (isset($match[0])) {
    $url = $match[0];
// Replace content with link
    $content = str_replace($url,'<a href="'.$url.'" target="_blank">'.$url.'</a>', $content);
}

// Add content back to message object
$message->data->content = $content;
echo json_encode($message);


