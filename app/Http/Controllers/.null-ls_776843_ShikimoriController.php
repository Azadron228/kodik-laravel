<?php

namespace App\Http\Controllers;

use App\Services\ShikimoriService;
use Illuminate\Http\Request;

class ShikimoriController extends Controller
{
    private $shikimoriService;
    protected $kodikService;


    public function __construct(ShikimoriService $shikimoriService)
    {
        $this->shikimoriService = $shikimoriService;
    }

    public function __construct(KodikService $kodikService)
    {
        $this->kodikService = $kodikService;
    }

    public function search($shikimoriId)
    {
        $result = $this->kodikService->searchAnimeByShikimoriId($shikimoriId, null);
        return response()->json($result);
    }

    public function getAnimes(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input('limit', 16);
        $page = $request->input('page', 1);
        $censored = $request->input('censored', true);

        $shikimoriResult = $this->shikimoriService->searchAnimes($search, $limit, $page, $censored);
    }
}
