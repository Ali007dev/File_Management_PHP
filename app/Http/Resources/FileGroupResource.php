<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileGroupResource extends JsonResource
{
    use ResourcePaginator;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
