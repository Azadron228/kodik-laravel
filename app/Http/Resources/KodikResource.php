<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KodikResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this['title'],
            'title_orig' => $this['title_orig'],
            'other_title' => $this['other_title'],
            'translation' => $this['translation'],
            'shikimori_id' => $this['shikimori_id'],
            'link' => 'https:' . $this['link'],
        ];
    }
}
