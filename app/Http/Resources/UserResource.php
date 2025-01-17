<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ResourcePaginator;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'number' => $this->number,
            'last_files' => FileResource::collection($this->whenLoaded('files')),
            'file_logs' => FileLogResource::collection($this->whenLoaded('fileLogs')),

        ];
    }
}
