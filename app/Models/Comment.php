<?php

namespace App\Models;

class Comment extends Model
{
    //
    public static $rules = [
        'comment' => 'required|string|min:2',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
