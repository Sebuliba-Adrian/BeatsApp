<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public static $rules = [
        'name' => 'required|unique:users',
        'password' => 'required',
        'email' => 'required|email|unique:users',
        'c_password' => 'required|same:password',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','is_artist'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param Album $album
     *
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function createAlbum(Album $album)
    {
        return $this->albums()->save($album);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    /**
     * @param $data
     * @param $albumId
     *
     * @return bool|int
     */
    public function updateAlbum($data, $album)
    {
        return $this->albums()->find($album->id)->update($data);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function deleteAlbum($album)
    {
        return $this->albums()->find($album->id)->delete();
    }

    /**
     * @param Track $track
     * @param $$album
     *
     * @return mixed
     */
    public function addTrack(Track $track, $album)
    {
        return $this->albums()->find($album->id)->tracks()->create(
            [
            "title" => $track->title,
            "file_url" => $track->file_url]
        );
    }

    /**
     * @param $data
     * @param $album
     * @param $track
     *
     * @return mixed
     */
    public function updateTrack($data, $album, $track)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->update($data);
    }

    /**
     * @param $album
     * @param $track
     *
     * @return mixed
     */
    public function deleteTrack($album, $track)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->delete();
    }

    /**
     * @param $album
     * @param $track
     * @param $comment
     *
     * @return mixed
     */
    public function addComment($album, $track, $comment)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->comments()->save($comment);
    }

    /**
     * @param $newComment
     * @param $album
     * @param $track
     * @param $comment
     *
     * @return mixed
     */
    public function updateComment($newComment, $album, $track, $comment)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->comments()->find($comment->id)->update($newComment);
    }

    public function listComments($album, $track)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->comments()->get();
    }

    public function showComments($album, $track, $comment)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->comments()->find($comment->id);
    }

    /**
     * @param $album
     * @param $track
     * @param $comment
     *
     * @return mixed
     */
    public function deleteComment($album, $track, $comment)
    {
        return $this->albums()->find($album->id)->tracks()->find($track->id)->comments()->find($comment->id)->delete();
    }

    /**
     * @param $playlist
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function createPlaylist($playlist)
    {
        return $this->playlists()->save($playlist);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * @param $playlist
     * @param $track
     *
     * @return bool
     */
    public function addTrackToPlaylist($playlist, $track)
    {
        if ($this->playlists()->find($playlist->id)->tracks->contains($track)) {
            return false;
        }
        return $this->playlists()->find($playlist->id)->tracks()->attach($track);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
