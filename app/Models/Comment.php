<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment', 'track_id', 'user_id',];
    //
    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
