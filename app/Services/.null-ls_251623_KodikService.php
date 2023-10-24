<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class KodikService
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://kodikapi.com/',

            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function rot13_base64_decode($encodedText)
    {
        $rot13Decoded = str_rot13($encodedText);
        $base64Decoded = base64_decode($rot13Decoded);
        return $base64Decoded;
    }

    public function processUrl($url, $api_token)
    {
        $pattern = '/^([^:]+):\/\/([a-z0-9]+\.[a-z]+)\/([a-z]+)\/(\d+)\/([0-9a-z]+)\/(\d+p)$/';
        preg_match($pattern, $url, $matches);
        list(, $protocol, $host, $type, $id, $hash, $quality) = $matches;

        $response = Http::post("$protocol://$host/gvi", [
            'id' => $id,
            'hash' => $hash,
            'token' => $api_token,
            'type' => $type,
            'quality' => $quality,
        ]);

        return $response->json();
    }

    public function searchAnime($shikimoriId)
    {
        try {
            $response = $this->httpClient->get("search?token=7ee795cd3e4f7078e7f5d430569d6559&shikimori_id=$shikimoriId");

            // Parse JSON response
            $data = json_decode($response->getBody(), true);

            return $data;
        } catch (\Exception $e) {
            // Handle exception (e.g., log or return an error response)
            return ['error' => $e->getMessage()];
        }
    }


    public function getEpisodeUrl()
    {
        $base_url = "//kodik.info/serial/45876/1982f6d94b38a0a11f3abdb275786044/720p";
        $client = new Client();
        $iframeResponse = $client->get("https:" . $base_url)->getBody();
        preg_match('/domain = "([^"]+)"/', $iframeResponse, $matchesDomain);
        preg_match('/d_sign = "([^"]+)"/', $iframeResponse, $matchesDSign);
        preg_match('/pd = "([^"]+)"/', $iframeResponse, $matchesPd);
        preg_match('/pd_sign = "([^"]+)"/', $iframeResponse, $matchesPdSign);
        preg_match("/videoInfo.type = '([^']+)'/", $iframeResponse, $matchesType);
        preg_match("/videoInfo.hash = '([^']+)'/", $iframeResponse, $matchesHash);
        preg_match("/videoInfo.id = '([^']+)'/", $iframeResponse, $matchesId);

        $url = "https://" . $matchesDomain[1] . "/gvi";

        $formData = [
            "d" => $matchesDomain[1],
            "d_sign" => $matchesDSign[1],
            "pd" => $matchesPd[1],
            "pd_sign" => $matchesPdSign[1],
            "ref" => "",
            "bad_user" => "false",
            "type" => $matchesType[1],
            "hash" => $matchesHash[1],
            "id" => $matchesId[1]
        ];

        $response = $client->post($url, [
            'form_params' => $formData
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
