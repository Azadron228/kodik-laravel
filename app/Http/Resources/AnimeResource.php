<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageLinks = [];
        foreach ($this['image'] as $link) {
            $imageLinks[] = 'https://shikimori.me' . $link;
        }
        return [
            'name' => $this['name'],
            'russian' => $this['russian'],
            'shikimori_id' => $this['id'],
            'image' => $imageLinks,
            'url' => 'https://shikimori.me' . $this['url'],
            'kind' => $this['kind'],
            'score' => $this['score'],
            'status' => $this['status'],
            'episodes' => $this['episodes'],
            'translation' => $this['translations'],
            'episodes_aired' => $this['episodes_aired'],
            'aired_on' => $this['aired_on'],
            'released_on' => $this['released_on'],
        ];
    }
}
