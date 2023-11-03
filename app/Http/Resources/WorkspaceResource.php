<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'background_color' => $this->background_color,
            'background_image' => $this->background_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'boards' => $this->boards,
        ];
    }
}
