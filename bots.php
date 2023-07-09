<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

global $CFG, $WS, $VIEW;
$page_number = $_GET['page'];
$page = '';
if ($page_number) {
    $page = '?page=' . $page_number;
}

$headers = $WS->get_headers($CFG->api_key);
// Get Bots
$bots = $WS->send_curl_request(
    'GET',
    $headers,
    $CFG->api_url . '/bots' . $page
);

$bots = json_decode($bots);

$bot_array = $bots->data;
$pagination = $bots->meta->pagination;
//print_object($pagination);
$pages = [];
for ($i = 0; $i < $pagination->total_pages; $i++) {
    $pages[$i]['number'] = $i + 1;
}

if ($pagination->current_page == 1) {
    $previous = false;
} else {
    $previous = $pagination->current_page - 1;
}

if ($pagination->current_page == $pagination->total_pages) {
    $next = false;
} else {
    $next = $pagination->current_page + 1;
}


$data = [
    'title' => 'Bots',
    'bots' => $bot_array,
    'pages'=> $pages,
    'previous' => $previous,
    'next' => $next,
];
//print_object($data);

$bot_page = $VIEW->loadTemplate('get_bots_conversations');
echo $bot_page->render($data);

//print_object($spaces);



