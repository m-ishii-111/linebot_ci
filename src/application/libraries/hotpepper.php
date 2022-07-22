<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotpepper
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = getenv('HOTPEPPER_API_KEY');
    }

    public function callGenreMaster()
    {
        $baseUri = 'https://webservice.recruit.co.jp/hotpepper/genre/v1/';
        $options = [
            'key' => $this->apiKey,
            'format' => 'json'
        ];

        $response = $this->sendCurl($baseUri, 'GET', $options);
        return $response['results'];
    }

    public function callRestaurants(string $latitude, string $longitude)
    {
        $baseUri = 'http://webservice.recruit.co.jp/hotpepper/gourmet/v1/';
        $options = [
            'key'       => $this->apiKey,
            'format'    => 'json',
            'lat'       => $latitude,
            'lng'       => $longitude,
            'range'     => '2',
            'order'     => '4',
            'datum'     => 'world',
            'count'     => '50',
        ];

        $response = $this->sendCurl($baseUrl, 'POST', $options);
        return $response['results'];
    }

    public function sendCurl(string $uri, string $method = 'GET', array $options = [], string $token = ''): array
    {
        if (empty($uri)) return [];

        if ($method == 'GET') {
            $uri .= '?' . http_build_query($options);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'POST') {
            $header = [ 'Content-Type: application/json; charser=UTF-8' ];
            if ($token != '') {
                $header[] = 'Authorization: Bearer ' . $token;
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}