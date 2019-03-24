<?php

namespace App\Models;

/**
 * @property  file_url
 */
class Track extends Model
{
    public static $rules = [
        "title" => "required|string|min:2|unique:tracks",
        'file_url' => 'mime:mpga,mp4,mov,ogg,wav,qt,dvd,mkv,',
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
