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

if (!isset($_SESSION['CODYBOT_USER'])) {
    header('Location: '. $CFG->wwwroot . '/login/');
}

$page_number = '';
if (isset($_GET['page'])) {
    $page_number = $_GET['page'];
}

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



