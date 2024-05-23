<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DRolesDefaultResource extends JsonResource
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
            'name' => $this->name,
            'author' => new DUsersDefaultResource($this->whenLoaded('relAuthor')),
            'parentRole' => new DRolesDefaultResource($this->whenLoaded('relParentRole')),
            'users' => DUsersDefaultResource::collection($this->whenLoaded('relUsers')),
        ];
    }
}
