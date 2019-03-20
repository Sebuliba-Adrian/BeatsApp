<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Track;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']) ;
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id" =>$album->id]);
        $comment = factory(Comment::class)->create(['comment'=>"I freakin love this song!", 'user_id'=>$user->id , 'track_id'=>$track->id]);
        $this->assertInstanceOf(User::class, $comment->user()->first());
        $this->assertInstanceOf(Track::class, $comment->track()->first());
    }
}
