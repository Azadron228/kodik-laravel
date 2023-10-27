<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ShikimoriService
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://shikimori.me/api/',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }


    public function searchAnimes($search, $limit = 16, $page = 1)
    {
        $searchQuery = urlencode($search);

        $response = Http::get("https://shikimori.me/api/animes", [
            'limit' => $limit,
            'censored' => "true",
            'page' => $page,
            'search' => $searchQuery,
        ]);

        return json_decode($response->getBody(), true);
    }
}
