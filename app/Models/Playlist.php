<?php

namespace App\Models;

class Playlist extends Model
{
    public static $rules = [
        'title' => 'required|string|min:2|unique:playlists',
        'body' => 'required|string|min:2',
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
