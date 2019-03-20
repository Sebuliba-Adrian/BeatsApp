<?php

namespace Tests\Feature;

use App\Models\Genre;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authorized_user_can_create_genre()
    {
        $token = $this->super_authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', '/api/genres', [
            "name"=>"lugaflow",
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(["message","data"=>["name","updated_at"]]);
    }

    public function test_authenticated_user_can_see_all_genres()
    {   $token = $this->authenticate();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', '/api/genres');
        $response->assertStatus(200);
        $response->assertJsonStructure(["data"=>[["id","name","albums","created_at","updated_at"]]]);
    }

    public function test_authorized_user_can_delete_genre()
    {
        $token = $this->super_authenticate();
        $genre=factory(Genre::class)->create(['name'=>'pop']);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', '/api/genres/'.$genre->id);
        $response->assertStatus(200);
        $response->assertJson(["success"=>true, "message"=> "The genre has been deleted successfully"]);
    }
}
