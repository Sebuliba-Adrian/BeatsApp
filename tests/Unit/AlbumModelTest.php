<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Genre;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AlbumModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_album_has_belongsto_relationship_between_album_and_genre()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $this->assertInstanceOf(User::class,$album->user()->first());
        $this->assertInstanceOf(Genre::class,$album->genre()->first());
    }
}
