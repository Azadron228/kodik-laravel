<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShikimoriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ShikimoriId' => $this['id'],
            'image' => $this['image'],
            'url' => $this['url'],
            'kind' => $this['kind'],
            'score' => $this['score'],
            'status' => $this['status'],
            'episodes' => $this['episodes'],
            'episodes_aired' => $this['episodes_aired'],
            'aired_on' => $this['aired_on'],
            'released_on' => $this['released_on'],
        ];
    }
}
