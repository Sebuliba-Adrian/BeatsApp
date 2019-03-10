<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = ['title', 'file_url', 'album_id',];
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function playlist()
    {
        return $this->belongsToMany(Track::class);
    }
}
