<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PhaseResource;
use App\Http\Resources\CategoryResource;
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
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "phases"=> $this->phases ? PhasesResource::collection($this->phases->sortBy('position')->values()):null,
            "workspace_id" => $this->workspace? $this->workspace->id:null,
        ];
    }
}
