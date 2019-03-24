<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Track;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TrackControllerTest extends TestCase
{
    /**
     * Storage dependency to be injected to IoC Container
     * @var
     */
    protected $storage;

    /**
     * File dependency to be injected to IoC Container
     * @var
     */
    protected $file;

    /**
     * File System dependency to be injected to IoC Container
     * @var
     */
    protected $fileSystem;

    public function setUp():void
    {
        parent::setUp();

        //Setup mocks
        $this->storage = $this->mock('Illuminate\Contracts\Filesystem\Factory');
        $this->file = $this->mock('Illuminate\Contracts\Filesystem');
        $this->fileSystem = $this->mock('Illuminate\Filesystem\Filesystem');

    }

    /**
     * Mock Container dependencies
     *
     * @param string $class Class to mock
     *
     * @return void
     */
    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_canot_store_invalidupload_track()
    {
        Storage::fake('public');

        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $response = $this->withHeaders(
            [
            'Authorization' => 'Bearer '. $token,
            ]
        )->json(
            'POST',
            "/api/albums/$album->id/tracks",
            [
                "title" => "summer title",
                "file_url" =>$file = UploadedFile::fake()->create('summer_time.mp7', 10),
                ]
        );
        $response->assertStatus(500);
    }


    public function test_user_can_see_all_tracks()
    {
        Storage::fake('public');

        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders(
            [
                'Authorization' => 'Bearer '. $token,
            ]
        )->json(
            'GET',
            "/api/albums/$album->id/tracks"
        );
        $response->assertStatus(200);

        $response->assertJsonStructure(
            ["current_page",
            "data"=>
                [
                    ["id", "title","file_url","album_id","created_at","updated_at","deleted_at"]
                ],"first_page_url",
                 "from","last_page",
                 "last_page_url",
                 "next_page_url",
                 "path","per_page",
                 "prev_page_url",
                 "to",
                 "total",
                ]
        );
    }



    public function test_user_can_see_single_track()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders(
            [
                'Authorization' => 'Bearer '. $token,
            ]
        )->json(
            'GET',
            "/api/tracks/$track->id"
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(
            ["data"=>["id","title",
            "album"=>["id","user_id","genre_id","title","release_date","created_at","updated_at"],
            "track_url","created_at","updated_at"]
        ]
        );
    }

    public function test_user_can_update_track()
    {
        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders(
            [
                'Authorization' => 'Bearer '. $token,
            ]
        )->json(
            'PATCH',
            "/api/albums/$album->id/tracks/$track->id",
            ["title"=>"only time"]
        );
        $response->assertStatus(201);
        $response->assertJsonStructure(
            ["message","data"=>["id","title",
                "album_id",
                "file_url","created_at","updated_at", "deleted_at"]
            ]
        );
    }

    public function test_user_can_delete_track()
    {
        $token = $this->super_authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders(
            [
                'Authorization' => 'Bearer '. $token,
            ]
        )->json(
            'DELETE',
            "/api/albums/$album->id/tracks/$track->id"
        );
        $response->assertStatus(200);
        $response->assertJson(["message"=>"Track deleted successfully"]);
    }


    public function test_user_cannot_delete_track()
    {
        $token = $this->authenticate();
        $user = JWTAuth::setToken($token)->toUser();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $album = factory(Album::class)->create(['title'=>"pistols","genre_id" => $genre->id,"user_id"=>$user->id, "release_date"=>'2019-03-11 01:04:15',]);
        $track = factory(Track::class)->create(['title'=>"anywhere is","file_url" => "http://filefactory.com/file.dat","album_id"=>$album->id]);

        $response = $this->withHeaders(
            [
                'Authorization' => 'Bearer '. $token,
                'Content-Type'  => 'application/json'
            ]
        )->json(
            'PUT',
            "/api/albums/$album->id/tracks/$track->id"
        );
        $response->assertStatus(405);
        //$response->assertJson(["message"=>"Track deleted successfully"]);
    }


}
