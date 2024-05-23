<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DProcessesDefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'parentProcess' =>  $this->parentProcess,
            'path'          =>  $this->path,
            'level'         =>  $this->level,
            'type'          =>  $this->type,
            'sasId'         =>  $this->sasId,
            'bpmId'         =>  $this->bpmId,
            'name'          =>  $this->name,
            'code'          =>  $this->owner,
            'validFrom'     =>  $this->validFrom,
            'validUntil'    =>  $this->validUntil,
            'author'        =>  $this->author
            /*'name' => $this->name,
            'author' => new DUsersDefaultResource($this->whenLoaded('relAuthor')),
            'parentRole' => new DProcessesDefaultResource($this->whenLoaded('relParentRole')),
            'users' => DUsersDefaultResource::collection($this->whenLoaded('relUsers')),*/
        ];
    }
}
