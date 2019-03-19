<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Playlist;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Track;
use Tymon\JWTAuth\Facades\JWTAuth;

class TrackTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_track_belongs_to_playlist()
    {  
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();

        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);
        $playlist=factory(Playlist::class)->create(['title'=>'playlist1', "body"=>"A play list of only 90s music", "user_id"=>$user->id]);
        $track->playlists();
        $user->playlists()->find($playlist->id)->tracks()->attach($track);
        $this->assertInstanceOf(Track::class,$track);
        $this->assertInstanceOf(Playlist::class,$user->playlists()->find($playlist->id)->first());
        $this->assertEquals( $playlist->id, $user->playlists()->find($playlist->id)->first()->id);

    }
}
