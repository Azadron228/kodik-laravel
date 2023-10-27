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

    public function searchAnimeByShikimoriId($shikimoriId, $translationId)
    {
        try {
            $response = $this->httpClient->get("search?token=7ee795cd3e4f7078e7f5d430569d6559&shikimori_id=$shikimoriId");
            $data = json_decode($response->getBody(), true);
            // return $data;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        $collection = collect($data['results']);

        if ($translationId !== null) {
            $result = $collection->where('translation.id', $translationId);
            return $result->first();
        }
        return $collection;
    }

    public function getEpisodeUrlByKodikUrl($base_url)
    {
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

        $strrr = json_decode($response->getBody(), true);
        $links = $strrr['links'];

        $decodedLinks = [];

        foreach ($links as $link => $subArrays) {
            foreach ($subArrays as $subArray) {
                $decodedSrc = $this->rot13_base64_decode($subArray["src"]);
                $quality = $link;

                $decodedLinks[] = [
                    "src" => $decodedSrc,
                    "quality" => $quality
                ];
            }
        }
        return $decodedLinks;
    }
}
