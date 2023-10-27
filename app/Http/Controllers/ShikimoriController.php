<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimeResource;
use App\Http\Resources\ShikimoriResource;
use App\Http\Resources\KodikResource;
use App\Services\KodikService;
use App\Services\ShikimoriService;
use Illuminate\Http\Request;

class ShikimoriController extends Controller
{
    private $shikimoriService;
    protected $kodikService;


    public function __construct(ShikimoriService $shikimoriService, KodikService $kodikService)
    {
        $this->shikimoriService = $shikimoriService;
        $this->kodikService = $kodikService;
    }

    public function getAnimes(Request $request)
    {
        $shikimoriId = 32379;
        $search = $request->input('search');
        $limit = $request->input('limit', 16);
        $page = $request->input('page', 1);
        $censored = $request->input('censored', true);

        $shikimoriResult = $this->shikimoriService->searchAnimes($search, $limit, $page, $censored);
        $mergedAnimes = [];

        foreach ($shikimoriResult as $anime) {
            $translations = $this->kodikService->searchAnimeByShikimoriId($anime['id'], null);
            foreach ($translations as $translation) {
                $translationData[] = [
                    'id' => $translation['translation']['id'],
                    'title' => $translation['translation']['title']
                ];
            }
            $mergedAnime = array_merge($anime, ['translations' => $translationData]);
            $mergedAnimes[] = $mergedAnime;
        }

        return AnimeResource::collection($mergedAnimes);

        // return response()->json($mergedAnimes);
    }
}
