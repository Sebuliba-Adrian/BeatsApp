<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Track;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_comment()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', "/api/albums/$album->id/tracks/$track->id/comments", [
            'comment' => 'This song reminds me of my life before i was born!',
        ]);
        $response->assertStatus(201);

        $response->assertJson(["message"=>"Comment created successfully"]);
    }

    public function test_user_can_see_comments()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', "/api/albums/$album->id/tracks/$track->id/comments");
        $response->assertStatus(200);

        $response->assertJsonStructure(['message', 'data']);
    }


    public function test_user_can_see_single_comment()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $comment = factory(Comment::class)->create(['comment'=>"I freakin love this song!", 'user_id'=>$user->id, 'track_id'=>$track->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', "/api/albums/$album->id/tracks/$track->id/comments/$comment->id");
        $response->assertStatus(200);

        $response->assertJsonStructure(['message', 'data']);
    }


    public function test_user_update_a_comment()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $comment = factory(Comment::class)->create(['comment'=>"I freakin love this song!", 'user_id'=>$user->id, 'track_id'=>$track->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PATCH', "/api/albums/$album->id/tracks/$track->id/comments/$comment->id",[
            "comment"=> "Awesome music!"
            ]);
        $response->assertStatus(201);

        $response->assertJson(['message'=>'The comment has been updated']);
    }

    public function test_authenticated_can_delete_a_comment()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $comment = factory(Comment::class)->create(['comment'=>"I freakin love this song!", 'user_id'=>$user->id, 'track_id'=>$track->id]);

        $user = JWTAuth::setToken($token)->toUser();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', "/api/albums/$album->id/tracks/$track->id/comments/$comment->id");
        $response->assertStatus(200);

        $response->assertJson(['message'=>'The comment has been deleted']);
    }
}
