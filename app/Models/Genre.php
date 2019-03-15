<?php

namespace App\Models;

class Genre extends Model
{
    public static $rules = [
        'name' => 'required|string|min:2|unique:genres'
    ];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }
}
