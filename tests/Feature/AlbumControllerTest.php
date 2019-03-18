<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Genre;
use App\Models\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class AlbumControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_create_album()
    {
        $response = $this->json('POST', '/api/albums', [
            'title' => 'pottle',
            'genre' => 1,
            'release_date' => '2019-03-11 01:04:15'
        ]);
        $response->assertStatus(401)->assertJson([]);
    }

    public function test_unauthorized_user_cannot_create_album()
    {
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', '/api/albums', [
            'title' => 'pottle',
            'genre_id' => $genre->id,
            'release_date' => '2019-03-11 01:04:15'
        ]);
        $response->assertStatus(403);
    }

    public function test_authorized_user_can_create_album()
    {
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $token = $this->super_authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', '/api/albums', [
            'title' => 'pottle',
            'genre_id' => $genre->id,
            'release_date' => '2019-03-11 01:04:15'
        ]);
        $response->assertStatus(201);
    }

    public function test_authenticated_user_can_fetch_albums()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', '/api/albums');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_see_album()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', '/api/albums/'.$album->id);
        $response->assertStatus(200)
        ->assertJsonStructure(["id", "user_id", "genre_id", "title", "release_date", "created_at", "updated_at"]);
    }

    public function test_authorized_user_can_delete_album()
    {
        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', '/api/albums/'.$album->id);
        $response->assertStatus(200)
        ->assertJson(["message"=>"The album has been deleted" ]);
    }

    public function test_authorized_user_cannot_delete_non_existing_album()
    {
        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', '/api/albums/-1');
        $response->assertStatus(404);
    }

    public function test_authorized_user_can_update_album()
    {
        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PATCH', '/api/albums/'.$album->id, [
            'title' => "one more time"
        ]);
        $response->assertStatus(200)
            ->assertJson(["message"=>"The album has been updated successfully" ]);
    }
}
