<?php

namespace App\Models;

class SettingsImages extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'settings_images';

    protected $fillable = [
        'type', 'path', 'thumb_path',
        'order'
    ];
    public function getRules($request, $item = null)
    {
        $rules = [
            'order'         => ['integer']
        ];

        return $rules;
    }

}

