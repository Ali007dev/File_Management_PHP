<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLogResource extends JsonResource
{
    use ResourcePaginator;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'operation' => $this->operation,
            'user' => UserResource::make($this->whenLoaded('user')),
            'file' => FileResource::make($this->whenLoaded('MFile')),

        ];
    }
}
