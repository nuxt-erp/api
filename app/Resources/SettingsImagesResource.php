<?php

namespace App\Resources;

use App\Resources\ResourceService;
use Illuminate\Support\Facades\Storage;

class SettingsImagesResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'            => $this->id,
            'type'          => $this->type,
            'path'          => $this->path,
            'thumb_path'    => $this->thumb_path,
            'order'         => $this->order
        ];
    }
}
