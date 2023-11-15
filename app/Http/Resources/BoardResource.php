<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PhaseResource;
 
class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "view" => $this->view,
            "background_color" => $this->background_color,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "phases" => $this->phases ? PhaseResource::collection($this->phases->sortBy('position')->values()) : null,
            "workspace_id" => $this->workspace ? $this->workspace->id : null,
            'user' => $this->users,
        ];
    }
}
