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
namespace cody_bot;

 class webservice
{

    /**
     * @param string $method POST|GET
     * @param array $headers
     * @param string $url
     * @param string $post_fields
     * @return bool|string
     */
    public function send_curl_request($method, $headers, $url, $post_fields = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Return header array for GET or POST
     * @param $method default 'GET' Available 'POST'
     * @param $token
     * @return string[]
     */
    public function get_headers($token, $method = 'GET' ) {
        if ($method == 'GET') {
            $headers = array(
                "Accept: application/json",
                "Authorization: Bearer $token",
            );
        } else {
            $headers = array(
                "Content-type: application/json",
                'Accept: application/json',
                "Authorization: Bearer $token",
            );
        }
        return $headers;
    }
}