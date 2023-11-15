<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\AttachmentResource;

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
            'groups' =>  $this->groups ? GroupResource::collection($this->groups) : null,
            'phase_id' => $this->phase ? $this->phase->id : null,
            'categories' =>  $this->categories ? $this->categories->sortBy('created_at')->values() : null,
            'comments' =>  $this->comments ? CommentResource::collection($this->comments->sortByDesc('created_at')->values()) : null,
            'attachments' =>  $this->attachments ? AttachmentResource::collection($this->attachments->sortBy('created_at')->values()) : null,
            'members' =>  $this->users? $this->users->sortBy('created_at')->values():null,
        ];
    }
}
