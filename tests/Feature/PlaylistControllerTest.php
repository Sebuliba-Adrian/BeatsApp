<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Playlist;
use App\Models\Track;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PlaylistControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_playlist()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', "/api/playlists", ['title'=>'playlist1', "body"=>"Only 90s music"]);

        $response->assertStatus(201);
        $response->assertJsonStructure(["message","data"=>["title","body","user_id","updated_at","created_at","id"]]);
    }

    public function test_user_can_see_playlists()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();

        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $playlist=factory(Playlist::class)->create(['title'=>'playlist1', "body"=>"A play list of only 90s music", "user_id"=>$user->id]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', "/api/playlists");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            ["data"=>[
            ["id","title","body","tracks",
                "creator"=>["id","name","email","biography","is_artist","email_verified_at","created_at","updated_at"]
            ]
        ],
            "links"=>["first","last","prev","next"],
            "meta"=>["current_page","from","last_page","path","per_page","to","total"]
        ]
        );
    }

    public function test_user_can_see_single_playlist()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();

        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $playlist=factory(Playlist::class)->create(['title'=>'playlist1', "body"=>"A play list of only 90s music", "user_id"=>$user->id]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', "/api/playlists/$playlist->id/tracks");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            ["data"=>["id","title","body","tracks","creator"=>
            ["id","name","email","biography","is_artist","email_verified_at","created_at","updated_at"]
            ]
            ]
        );
    }

    public function test_user_can_add_track_to_playlist()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();

        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $playlist=factory(Playlist::class)->create(['title'=>'playlist1', "body"=>"A play list of only 90s music", "user_id"=>$user->id]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', "/api/playlists/$playlist->id/tracks/$track->id");

        $response->assertStatus(201);
        $response->assertJson(["success"=>true, "message"=>"Track added to playlist"]);
    }

    public function test_user_cannot_add_duplicate_track_to_playlist()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();

        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $playlist=factory(Playlist::class)->create(['title'=>'playlist1', "body"=>"A play list of only 90s music", "user_id"=>$user->id]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', "/api/playlists/$playlist->id/tracks/$track->id");

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', "/api/playlists/$playlist->id/tracks/$track->id");

        $response->assertStatus(400);
        $response->assertJson(["success"=>false, "message"=>"Track already exists in the playlist"]);
    }
}
