<?php

namespace App\Models;

class Album extends Model
{
    public static $rules = [
        'title' => 'required|string|min:2|unique:albums',
        'genre_id' => 'required',
        'release_date' => 'required',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
