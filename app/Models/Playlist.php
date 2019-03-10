<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['title', 'body',];
    public function track()
    {
        return $this->belongsToMany(Track::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
