<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KodikService;

class KodikController extends Controller
{
    protected $kodikService;

    public function __construct(KodikService $kodikService)
    {
        $this->kodikService = $kodikService;
    }

    public function search($shikimoriId)
    {
        $result = $this->kodikService->searchAnimeByShikimoriId($shikimoriId, null);
        return response()->json($result);
    }

    public function episode(Request $request)
    {
        $baseUrl = $request->input('url');
        $episode = $request->input('episode');
        $url = $baseUrl . "?hide_selectors=true&episode=" . $episode;
        $resulst = $this->kodikService->getEpisodeUrlByKodikUrl($url);
        return response()->json($resulst);
    }
}
