<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

global $CFG, $WS, $VIEW, $DB;

// Get
$prompt = $_REQUEST['prompt'];
$headers = $WS->get_headers('POST', $CFG->api_key);

// Get client IP
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
// Lea bot id = K4oeEl4Bld0B
// Conversation id = 4openZN3Pe7A



$content = '{"content": "' . $prompt . ' Also, in curly brackets, return the language name (only) the response is in", "conversation_id":"4openZN3Pe7A"}';
$message = $WS->send_curl_request(
    'POST',
    $headers,
    'https://getcody.ai/api/v1/messages',
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

// Because the response is returning the langugae it is written in, I want to remove that from the content
// Split the content into an array of sentences based on period
$sentences = explode(".", $content);

$length = count($sentences);

$last_sentence = $sentences[$length - 2];

// Remove the last sentence from the content
$content = str_replace($last_sentence, '', $content);
// Convert last sentence to lower case
$last_sentence = strtolower($last_sentence);
// Replace all  "français" with "french"
$language = str_replace('français', 'french', $last_sentence);


// get url from content
preg_match('/http(.*)\/\/\S+/', $content, $match);
if (isset($match[0])) {
    $url = $match[0];
// Replace content with link
    $content = str_replace($url,'<a href="'.$url.'" target="_blank">'.$url.'</a>', $content);
}

//echo $check_language->data->content;
if (strpos($language, 'french') === false ) {
    $content .= "<br><br>You can find more information at <a href=\"https://www.glendon.yorku.ca\" target=\"_blank\">Glendon</a>";
} else {
    $content .= "<br><br>Vous pouvez trouver plus d'information au site web de <a href=\"https://www.glendon.yorku.ca\" target=\"_blank\">Glendon</a>";
}

// Add content back to message object
$message->data->content = $content;
echo json_encode($message);


