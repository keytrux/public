<?php

namespace App\Helpers;

class Request
{
    public static function send(string $url, string $method = 'GET', ?array $data = [], string $type = 'json', array $params = [], array $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            switch ($type) {
                case 'json':
                    $data = json_encode($data);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));
                    break;
                case 'urlencoded':
                    $data = http_build_query($data);
                    break;
            }

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= "?$query";
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $out = curl_exec($curl);

        curl_close($curl);

        return json_decode($out, true);
    }
}