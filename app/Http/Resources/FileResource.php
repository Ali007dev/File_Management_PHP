<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    use ResourcePaginator;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'path' => $this->path,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'file_logs' => FileLogResource::collection($this->whenLoaded('fileLogs')),
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'last_modify' => FileLogResource::make($this->whenLoaded('lastModify')),
            'last_view' => FileLogResource::make($this->whenLoaded('lastView')),
           'archive' => $this->whenLoaded('archive', function () {
            return $this->archive->map(function ($archiveItem) {
                return [
                    'date' => $archiveItem->date,
                    'id' => $archiveItem->id,
                ];
            });
        }),



        ];
    }
}
