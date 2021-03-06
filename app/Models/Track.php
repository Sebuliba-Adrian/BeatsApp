<?php

namespace App\Models;

class Track extends Model
{
    public static $rules = [
        "title" => "required|string|min:2|unique:tracks",
        'file_url' => 'required|mimes:mpga,mp4,wav,avi,mkv,dvd',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Track::class);
    }

    public function scopeOfAlbum($query, $albumId)
    {
        return $query->where("album_id", $albumId);
    }
}
