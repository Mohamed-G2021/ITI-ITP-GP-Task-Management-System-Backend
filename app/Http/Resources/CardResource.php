<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'due_date' => $this->due_date,
            'status_icon' => $this->status_icon,
            'position' => $this->position,
            'phase' => $this->phase ? $this->phase : null,
            'groups' =>  $this->groups ? GroupResource::collection($this->groups) : null,
        ];
    }
}
