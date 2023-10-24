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

    public function search(Request $request)
    {
        $shikimoriId = 51009;

        $result = $this->kodikService->searchAnime($shikimoriId);
        return response()->json($result);
    }



    public function episode(Request $request)
    {
        $url = 'https://kodik.info/serial/51979/560bf32cb57c16e09e21b9046ebe4eff/720p';

        $resulst = $this->kodikService->getEpisodeUrl($url, 5);
        return response()->json($resulst);
    }
}
