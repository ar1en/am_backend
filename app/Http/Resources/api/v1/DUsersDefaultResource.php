<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\api\v1\DVacanciesDefaultResource;
use App\Http\Resources\api\v1\DRolesDefaultResource;

class DUsersDefaultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'vacancies' => DVacanciesDefaultResource::collection($this->whenLoaded('vacancies')),
            'roles' => DRolesDefaultResource::collection($this->whenLoaded('roles')),
        ];
    }
}
