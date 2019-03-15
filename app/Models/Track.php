<?php

namespace App\Models;

class Track extends Model
{
    public static $rules = [
        "title" => "required|sting|min:2|unique:tracks",
        'file_url' => 'required|mimes:audio/mpeg,mp4,mp3,avi,mkv,dvd',
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
